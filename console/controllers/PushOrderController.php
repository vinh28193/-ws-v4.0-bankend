<?php


namespace console\controllers;


use common\components\boxme\BoxMeClient;
use common\models\Order;
use yii\console\Controller;

class PushOrderController extends Controller
{
    public function actionPushOrder($rows, $store_id = null,$env = 'prod')
    {
        print_r(YII_ENV);
        $this->stdout_F('Rows: '.$rows);
        $this->stdout_F('Bắt đầu chạy job: ');
        /** @var Order[] $orders */
        $qr = Order::find()
            ->where(['is not', 'tracking_codes', null])
            ->andWhere(['ordercode' => '277084'])
            ->andWhere(['<>', 'tracking_codes', ''])
            ->andWhere(['or', ['order_boxme' => ''], ['is', 'order_boxme', null]])
            ->andWhere(['or', ['shipment_boxme' => ''], ['is', 'shipment_boxme', null]]);
        if($store_id){
            $qr->andWhere(['store_id' => $store_id]);
        }
        $orders = $qr->limit($rows)->all();
        $this->stdout_F('Có '.count($orders).' orders sẽ được chạy');
        foreach ($orders as $order) {
            $this->stdout_F('Chạy đơn: '.$order->ordercode);
            $this->stdout_F('Bắt đầu sync product:... ');
            foreach ($order->products as $product) {
                $this->stdout_F('sync product: '.$product->sku.' - '.$product->parent_sku);
                $productBM = BoxMeClient::SyncProduct($product);
                print_r($productBM);
                $this->stdout_F('');
                if(is_array($productBM) && isset($productBM[0]) && !$productBM[0]){
                    $this->stdout_F('sync false.!');
                }else{
                    $this->stdout_F('sync success.!');
                }
            }
            if($order->order_boxme){
                $this->stdout_F('Đã có order boxme: '.$order->order_boxme);
            }else{
                $this->stdout_F('Tạo order box me: ...');
                $orderBM = BoxMeClient::CreateOrder($order);
                print_r($orderBM);
                if(!$orderBM || (is_array($orderBM) && !$orderBM[0] )){
                    $this->stdout_F('Tạo order box me lỗi. Bỏ qua order.');
                    $this->stdout_F('-------ERROR----------');
                    continue;
                }
                $this->stdout_F('');
                $this->stdout_F('Tạo order box me success.');
            }

            $this->stdout_F('Tạo shipment box me: ...');
            print_r(BoxMeClient::CreateLiveShipment($order));
            $this->stdout_F('Tạo shipment box me success!');
            $this->stdout_F('---------SUCCESS---------');
        }
        $this->stdout_F('Job end ---------------------------');
    }
    public function actionPushShipment($rows,$env = 'prod')
    {
        $this->stdout_F('ENV: '.YII_ENV);
        $this->stdout_F('Rows: '.$rows);
        $this->stdout_F('Bắt đầu chạy job: ');
        /** @var Order[] $orders */
        $orders = Order::find()
            ->where(['is not', 'tracking_codes', null])
            ->andWhere(['<>', 'tracking_codes', ''])
            ->andWhere(['or', ['<>' , 'order_boxme' , ''], ['is not', 'order_boxme', null]])
            ->andWhere(['or', ['shipment_boxme' => ''], ['is', 'shipment_boxme', null]])
            ->limit($rows)->all();
        $this->stdout_F('Có '.count($orders).' orders sẽ được chạy');
        foreach ($orders as $order) {
            $this->stdout_F('Chạy đơn: '.$order->ordercode);
            $this->stdout_F('Tạo shipment box me: ...');
            print_r(BoxMeClient::CreateLiveShipment($order));
            $this->stdout_F('');
            $this->stdout_F('---------DONE---------');
        }
        $this->stdout_F('Job end ---------------------------');
    }
    public function stdout_F($string,$option = '')
    {

        return parent::stdout($string."\n",$option); // TODO: Change the autogenerated stub
    }
}
