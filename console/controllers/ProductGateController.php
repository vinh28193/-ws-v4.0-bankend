<?php


namespace console\controllers;


use common\additional\AdditionalFeeCalculator;
use common\helpers\WeshopHelper;
use common\models\cms\WsProduct;
use common\modelsMongo\ProductSyncLog;
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
        /** @var  $lastSync ProductSyncLog | null */
        $lastSync = ProductSyncLog::find()->limit(1)->where(['row_id' => ProductSyncLog::find()->max('row_id')])->orderBy(['time' => SORT_DESC])->one();
        if ($lastSync !== null) {
            $query->andWhere(['>', 'id', $lastSync->row_id]);
        }
        $totalFetch = (clone $query)->count();
        $limit = 100;
        $totalPage = ceil($totalFetch / $limit);
        $totalCount = 0;
        for ($page = 1; $page <= $totalPage; $page++) {
            $pStart = microtime(true);
            $offset = ($page - 1) * $limit;
            $records = (clone $query)->limit($limit)->offset($offset)->all();
            $this->stdout("    > loading page $page/$totalPage, limit $limit, offset $offset ...\n", Console::FG_GREEN);
            $count = 0;
            foreach ($records as $record) {
                $count++;
                /** @var  $record WsProduct */
                $log = new ProductSyncLog();
                $rStart = microtime(true);
                $itemType = WeshopHelper::isSubText($record->item_url, 'ebay/item') ? 'ebay' : 'amazon';
                $log->time = $now;
                $log->item_type = $itemType;
                $log->item_id = $record->item_id;
                $log->item_sku = $record->item_sku;
                $log->row_id = $record->id;

                $this->stdout("    > syncing record $count/$limit \n", Console::FG_GREEN);
                $this->stdout("    > record {$record->id} \n", Console::FG_GREEN);
                try {
                    $form = new ProductDetailFrom();
                    $form->type = WeshopHelper::isSubText($record->item_url, 'ebay/item') ? 'ebay' : 'amazon';
                    $form->id = $record->item_id;
                    $form->sku = $record->item_sku;
                    if (($product = $form->detail()) === false) {
                        $log->results = $form->getErrors();
                        $log->action = 'delete';
                        $log->save(false);
                        $this->stdout("    > not found item {$form->type} {$form->id} {$form->sku} \n", Console::FG_RED);
                        $record->updateAttributes(['status' => 0]);
                        continue;
                    }
                    $update['weight'] = $product->getShippingWeight();
                    $update['sell_price'] = $product->getSellPrice();
                    $update['start_price'] = $product->start_price;
                    $update['calculated_sell_price'] = WeshopHelper::roundNumber($product->getLocalizeTotalPrice() / $product->getExchangeRate(), 2);
                    $update['calculated_start_price'] = WeshopHelper::roundNumber($product->getLocalizeTotalStartPrice() / $product->getExchangeRate(), 2);
                    if ($product->getSellPrice() === 0 || count($product->providers) === 0) {
                        $log->results = [
                            'sell_price' => $product->getSellPrice(),
                            'providers' => $product->providers
                        ];
                        $log->action = 'delete';
                        $log->save(false);
                        $record->updateAttributes(['status' => 0]);
                        continue;
                    }
                    if ($record->provider !== null) {
                        foreach ($product->providers as $provider) {
                            if ($provider->name === $record->provider) {
                                $product->updateBySeller($provider->prov_id);
                                break;
                            }
                        }
                    }
                    $product->description = null;
                    $product->technical_specific = null;
                    $log->results = [
                        'oldValue' => [
                            'weight' => $record->weight,
                            'sell_price' => $record->sell_price,
                            'start_price' => $record->start_price,
                            'calculated_sell_price' => $record->calculated_sell_price,
                            'calculated_start_price' => $record->calculated_start_price,
                        ],
                        'updateValue' => $update,
                        'product' => $product,
                        'productFees' => $product->getAdditionalFees()->toArray()
                    ];
                    $log->action = 'complete';
                    $log->save(false);
                    $record->updateAttributes($update);
                    $rTime = sprintf('%.3f', microtime(true) - $rStart);
                    $this->stdout("    > committed record $count in page $page  (time $rTime s) \n", Console::FG_GREEN);
                } catch (\Exception $exception) {
                    $this->stdout("    > {$exception->getMessage()} \n", Console::FG_RED);
                    $log->results = $exception->getMessage();
                    $log->action = 'exception';
                    $log->save(false);
                    $record->updateAttributes(['status' => 0]);
                }
            }
            $pTime = sprintf('%.3f', microtime(true) - $pStart);
            $this->stdout("    > committed $count records in page $page/$totalPage  (time $pTime s) \n", Console::FG_GREEN);
            sleep(20);
            $this->stdout("    > action will be continue after sleep.. \n", Console::FG_GREEN);
        }
        $time = sprintf('%.3f', microtime(true) - $start);
        $this->stdout("    > action ended execute $totalCount/$totalFetch records (time $time s) \n", Console::FG_GREEN);
        return ExitCode::OK;
    }

}