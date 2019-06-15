<?php


namespace console\controllers;

use common\components\db\Connection;
use common\models\Category;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Console;

class CategoryController extends Controller
{

    public $color = true;

    public function actionSyncGroup()
    {
        $start = microtime(true);
        /** @var  $db Connection */
        $db = Yii::$app->get('db');
        /** @var  $db_cms Connection */
        $db_cms = Yii::$app->get('db_cms');
        $tableName = Category::tableName();
        $this->stdout("    > open connect to dsn:`{$db_cms->dsn}` \n", Console::FG_GREEN);
        $fetchQuery = new Query();
        $fetchQuery->from(['c' => $tableName]);
        $this->stdout("    > connected to dsn `{$db_cms->dsn}` table `$tableName`, fetching ....\n", Console::FG_GREEN);
        $totalFetch = (clone $fetchQuery)->count('[[id]]', $db_cms);
        $this->stdout("    > dsn `{$db_cms->dsn}` fetched $totalFetch records....\n", Console::FG_GREEN);
        if (!$this->confirm("    > do you want to sync group for $totalFetch category")) {
            $this->stdout("    > action cancel\n", Console::FG_GREEN);
            return ExitCode::OK;
        }
        $this->stdout("    > loading group mapping...\n", Console::FG_GREEN);
        $mappingGroup = require dirname(dirname(__DIR__)) . '/common/models/category_group_map.php';
        $this->stdout("    > open connect to dsn:`{$db->dsn}` \n", Console::FG_GREEN);
        $this->stdout("    > connected to dsn `{$db->dsn}`\n", Console::FG_GREEN);
        //$db->createCommand('SET FOREIGN_KEY_CHECKS = 0;')->execute();
        $this->stdout("    > dsn {$db->dsn} execute 'SET FOREIGN_KEY_CHECKS = 0;'\n", Console::FG_GREEN);
        $this->stdout("    > dsn {$db->dsn} truncate table $tableName\n", Console::FG_GREEN);
        $db->createCommand()->truncateTable($tableName)->execute();
        $limit = 1000;
        $totalPage = ceil($totalFetch / $limit);
        $totalCount = 0;
        for ($page = 1; $page <= $totalPage; $page++) {
            $pStart = microtime(true);
            $offset = ($page - 1) * $limit;
            $this->stdout("    > dsn `{$db_cms->dsn}` loading page $page/$totalPage, limit $limit, offset $offset ...\n", Console::FG_GREEN);
            $records = (clone $fetchQuery)->limit($limit)->offset($offset)->all($db_cms);
            $transaction = $db->beginTransaction();
            try {
                $count = 0;
                foreach ($records as $index => $record) {
                    $qStart = microtime(true);
                    $this->stdout("    > syncing record $count/$limit \n", Console::FG_GREEN);
                    $this->stdout("    > category id `{$record['id']}`, name `{$record['name']}`, origin name `{$record['originName']}`\n", Console::FG_GREEN);
                    $db->createCommand()->insert($tableName, [
                        'id' => $record['id'],
                        'alias' => $record['alias'],
                        'site' => $this->portalMapping($record['siteId']),
                        'name' => $record['name'],
                        'origin_name' => $record['originName'],
                        'category_group_id' => isset($mappingGroup[$record['category_group_id']]) ? $mappingGroup[$record['category_group_id']] : null,
                        'parent_id' => $record['parentId'],
                        'description' => $record['description'],
                        'weight' => $record['weight'],
                        'inter_shipping_b' => $record['interShippingB'],
                        'custom_fee' => $record['customFee'],
                        'level' => $record['level'],
                        'path' => $record['path'],
                        'created_at' => Yii::$app->getFormatter()->asTimestamp('now'),
                        'updated_at' => Yii::$app->getFormatter()->asTimestamp('now'),
                        'active' => $record['active'],
                        'remove' => 0,
                        'version' => '4.0',
                    ])->execute();

                    $qTime = sprintf('%.3f', microtime(true) - $qStart);
                    $this->stdout("    > dsn `{$db->dsn}` query ok (time $qTime s)\n", Console::FG_GREEN);
                    $count++;
                }
                $totalCount += $count;
                $transaction->commit();
                $time = sprintf('%.3f', microtime(true) - $pStart);
                $this->stdout("    > dsn `{$db->dsn}` committed $count records in page $page  (time $time s) \n", Console::FG_GREEN);
            } catch (Exception $exception) {
                $this->stdout("    > {$exception->getMessage()}", Console::FG_RED);
                $transaction->rollBack();
                $this->stdout("    > dsn `{$db->dsn}` roll back transaction, page $page not commit\n", Console::FG_RED);
            }
        }
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 1;')->execute();
        $this->stdout("    > dsn `{$db->dsn}` execute 'SET FOREIGN_KEY_CHECKS = 1;'\n", Console::FG_GREEN);
        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("    > action ended execute $totalCount/$totalFetch records (time $time s) \n", Console::FG_GREEN);
        return ExitCode::OK;
    }

    private function portalMapping($site)
    {
        if (Category::SITE_EBAY == $site) {
            return 'ebay';
        } elseif (Category::SITE_EBAY == $site) {
            return 'amazon';
        } elseif (Category::SITE_AMAZON_JP == $site) {
            return 'amazon-jp';
        } elseif (Category::SITE_AMAZON_UK == $site) {
            return 'amazon-uk';
        } else {
            return null;
        }
    }
}