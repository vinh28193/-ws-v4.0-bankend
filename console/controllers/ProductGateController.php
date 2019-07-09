<?php


namespace console\controllers;


use common\additional\AdditionalFeeCalculator;
use common\helpers\WeshopHelper;
use common\models\cms\WsProduct;
use common\products\forms\ProductDetailFrom;
use yii\console\Controller;
use Yii;
use yii\console\ExitCode;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class ProductGateController extends Controller
{
    public function actionSyncProductCms()
    {
        $start = microtime(true);
        $now = Yii::$app->formatter->asDatetime('now');
        $this->stdout("action start at: $now", Console::FG_GREEN);
        $startFetchTime = microtime(true);
        $this->stdout("fetching ....", Console::FG_GREEN);
        $query = WsProduct::find()->where(['status' => 1]);

        $totalFetch = (clone $query)->count();
        $limit = 1000;
        $totalPage = ceil($totalFetch / $limit);
        $totalCount = 0;
        for ($page = 1; $page <= $totalPage; $page++) {
            $pStart = microtime(true);
            $offset = ($page - 1) * $limit;
            $records = (clone $query)->limit($limit)->offset($offset)->all();
            $this->stdout("    > loading page $page/$totalPage, limit $limit, offset $offset ...\n", Console::FG_GREEN);
            $count = 0;
            foreach ($records as $record) {
                /** @var  $record WsProduct */
                $rStart = microtime(true);
                $this->stdout("    > syncing record $count/$limit \n", Console::FG_GREEN);
                $this->stdout("    > record {$record->id} \n", Console::FG_GREEN);
                try {
                    $form = new ProductDetailFrom();
                    $form->type = WeshopHelper::isSubText($record->item_url, 'ebay/item') ? 'ebay' : 'amazon';
                    $form->id = $record->item_id;
                    $form->sku = $record->item_sku;
                    if (($product = $form->detail()) === false) {
                        $this->stdout("    > not found item {$form->type} {$form->id} {$form->sku} ", Console::FG_RED);
                        $record->updateAttributes(['status' => 0]);
                        continue;
                    }
                    $update['weight'] = $product->getShippingWeight();
                    $update['sell_price'] = $product->getSellPrice();
                    $update['start_price'] = $product->start_price;
                    $update['calculated_sell_price'] = WeshopHelper::roundNumber($product->getLocalizeTotalPrice() / $product->getExchangeRate(), 2);
                    $update['calculated_start_price'] = WeshopHelper::roundNumber($product->getLocalizeTotalStartPrice() / $product->getExchangeRate(), 2);
                    $record->updateAttributes($update);
                    $rTime = sprintf('%.3f', microtime(true) - $rStart);
                    $this->stdout("    > committed record $count in page $page  (time $rTime s) \n", Console::FG_GREEN);
                    sleep(5 * 60);
                } catch (\Exception $exception) {
                    $this->stdout("    > {$exception->getMessage()}", Console::FG_RED);
                    $record->updateAttributes(['status' => 0]);
                }
                $count++;
            }
            $pTime = sprintf('%.3f', microtime(true) - $pStart);
            $this->stdout("    > committed $count records in page $page  (time $pTime s) \n", Console::FG_GREEN);
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("    > action ended execute $totalCount/$totalFetch records (time $time s) \n", Console::FG_GREEN);
        return ExitCode::OK;
    }

}