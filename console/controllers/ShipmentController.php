<?php


namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\Shipment;
use common\boxme\forms\CreateOrderForm;

class ShipmentController extends Controller
{

    public function actionSend()
    {
        $start = microtime(true);
        $now = Yii::$app->formatter->asDatetime('now');
        $this->stdout("action start at: $now", Console::FG_GREEN);
        $startFetchTime = microtime(true);
        $this->stdout("fetching ....", Console::FG_GREEN);
        $shipments = Shipment::find()->where([
            'AND',
            ['status' => Shipment::STATUS_WAITING],
            ['active' => 1]
        ])->all();
        $totalShipment = count($shipments);
        $fetchTime = microtime(true) - $startFetchTime;
        $this->stdout("done (time: " . sprintf('%.3f', $fetchTime) . "s)", Console::FG_GREEN);
        $this->stdout("fetched $totalShipment shipment waiting", Console::FG_GREEN);
        $process = 1;
        $errors = [];
        foreach ($shipments as $shipment) {
            $createForm = new CreateOrderForm();
            /* @var $shipment Shipment */
            list($isSuccess, $mess) = $createForm->createByShipment($shipment);
            if($isSuccess === false){
                $this->stdout("error when send shipment {$shipment->id} : `$mess`", Console::FG_GREEN);
                $errors[$shipment->id] = $mess;
            }
            $process++;
        }
        $time = microtime(true) - $start;
        $this->stdout("action completed (time: " . sprintf('%.3f', $time) . "s)", Console::FG_GREEN);
    }
}