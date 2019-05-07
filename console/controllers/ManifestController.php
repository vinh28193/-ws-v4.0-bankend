<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-03
 * Time: 18:04
 */

namespace console\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Console;
use common\boxme\WarehouseInspect;
use common\boxme\WarehousePacking;
use common\models\Manifest;

class ManifestController extends \yii\console\Controller
{

    public function actionInspect()
    {
        $start = microtime(true);
        $now = Yii::$app->formatter->asDatetime('now');
        $this->stdout("action start at: $now", Console::FG_GREEN);
        $startFetchTime = microtime(true);
        $this->stdout("fetching ....", Console::FG_GREEN);
        $manifestQuery = new Query();
        $manifestQuery->from(['manifest' => Manifest::tableName()]);
        $manifestQuery->select(['code', 'id']);
        $manifestQuery->where([
            'AND',
            ['status' => Manifest::STATUS_INSPECTING],
            ['active' => 1]
            //['>=', 'id', 1083] // bắt đầu chạy từ ngày 12/05/2018 bắt đầu từ lô VA004(1083)
        ]);
        $totalManifest = (clone $manifestQuery)->count('id', Manifest::getDb());
        $fetchTime = microtime(true) - $startFetchTime;
        $this->stdout("done (time: " . sprintf('%.3f', $fetchTime) . "s)", Console::FG_GREEN);
        $this->stdout("fetched $totalManifest manifest", Console::FG_GREEN);
        $process = 1;
        foreach ($manifestQuery->all() as $manifest) {
            $manifestStart = microtime(true);
            $packingCode = implode('-', array_values($manifest));
            if (($code = $manifest['code']) === 'RVSID-003' || $code === 'VAID001' || $code === 'TON2512' || $code === 'RVSID004') {
                $packingCode = $code;
            }
            $manifestId = $manifest['id'];
            $this->stdout("process $process update $packingCode", Console::FG_GREEN);
            foreach (WarehouseInspect::inspect($packingCode) as $result) {
                $page = $result['page'];
                $totalItem = $result['totalItem'];
                $totalInspect = $result['totalInspect'];
                $token = "Page $page";
                if ($page !== 1) {
                    $token .= " ,have $totalItem item";
                }
                $token .= " ,completed $totalInspect items";

                $this->stdout($token, Console::FG_GREEN);
//                $this->consoleLog($result, "updatePage$page");
            }
            $process++;
            Manifest::updateAll([
                'status' => Manifest::STATUS_INSPECT_DONE
            ], ['id' => $manifestId]);
            $manifestTime = microtime(true) - $manifestStart;
            $this->stdout("update  packing $packingCode completed (time: " . sprintf('%.3f', $manifestTime) . "s)", Console::FG_GREEN);
//            $this->savePackingLog($packingCode);
        }
        $time = microtime(true) - $start;
        $this->stdout("action completed (time: " . sprintf('%.3f', $time) . "s)", Console::FG_GREEN);
    }

    public function actionCreate()
    {
        $start = microtime(true);
        $now = Yii::$app->formatter->asDatetime('now');
        $this->stdout("action start at: $now", Console::FG_GREEN);
        $startFetchTime = microtime(true);
        $this->stdout("fetching ....", Console::FG_GREEN);
        $manifestQuery = Manifest::find();
        $manifestQuery->with('packages');
        $manifestQuery->where([
            'AND',
            ['status' => Manifest::STATUS_PACKING_CREATING],
            ['active' => 1]
            //['>=', 'id', 1083] // bắt đầu chạy từ ngày 12/05/2018 bắt đầu từ lô VA004(1083)
        ]);
        $totalManifest = (clone $manifestQuery)->count('id', Manifest::getDb());
        $fetchTime = microtime(true) - $startFetchTime;
        $this->stdout("done (time: " . sprintf('%.3f', $fetchTime) . "s)", Console::FG_GREEN);
        $this->stdout("fetched $totalManifest manifest", Console::FG_GREEN);
        $process = 1;
        foreach ($manifestQuery->all() as $manifest) {
            /** @var  $manifest Manifest */
            $manifestStart = microtime(true);
            $this->stdout("process $process", Console::FG_GREEN);
            $res = (new WarehousePacking(['env' => WarehousePacking::ENV_PROD]))->create($manifest);
            $this->stdout($res['message'], $res['success'] ? Console::FG_GREEN : Console::FG_RED);
            $process++;
            $manifest->status = Manifest::STATUS_PACKING_CREATED;
            $manifest->save(false);
            $manifestTime = microtime(true) - $manifestStart;
            $this->stdout("update  packing {$manifest->manifest_code} completed (time: " . sprintf('%.3f', $manifestTime) . "s)", Console::FG_GREEN);
        }
        $time = microtime(true) - $start;
        $this->stdout("action completed (time: " . sprintf('%.3f', $time) . "s)", Console::FG_GREEN);
    }
}