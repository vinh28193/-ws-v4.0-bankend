<?php
/**
 * Created by PhpStorm.
 * User: phnam
 * Date: 11/15/17
 * Time: 8:25 AM
 */

namespace api\modules\v1\api\controllers;

use api\modules\v1\controllers\AuthController;
use common\components\ExcelSpreadsheet;
use common\components\TextUtility;
use common\components\UrlComponent;
use common\models\mongo_report\ReportCommand;
use common\models\mongo_report\ReportPurchaseData;
use common\models\db\Customer;
use common\models\db\Order;
use common\models\db\OrderItem;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ReportController extends AuthController
{

    public function actionIndex()
    {
        $data = [
            'all_order' => 200,
            'paid_order' => 12
        ];

        return new $this->response(true, "ok", $data);
    }

    public function actionCountmonth()
    {

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $first_date = date("Y-m-01 00:00:00");
        $last_date = date("Y-m-d H:i:s");

        $order = Order::find()
            ->where(['storeId' => 1])
            ->andwhere(['between', 'createTime', $first_date, $last_date]);

        $count_all_order = $order->count();
        $count_paid_order = $order->andWhere(['paymentStatus' => 2])->count();

        $data = [
            'all_order' => $count_all_order,
            'paid_order' => $count_paid_order
        ];

        return $this->response(true, "ok", $data);
    }

    public function actionLastpaid()
    {

        $last_paid_time = Order::find()
            ->where(['storeId' => 1])
            ->max('LastPaidTime');

        $order = Order::find()
            ->where(['`order`.`storeId`' => 1])
            ->andWhere(['!=', 'type', 6])
            ->andWhere(['!=', 'remove', 1])
            ->andWhere(['LastPaidTime' => $last_paid_time])
            ->leftJoin('order_item', '`order_item`.`orderId` = `order`.`id`')
            ->select('order.buyerName, order.buyerEmail, order.buyerPhone, order.buyerAddress, order.OrderTotalInLocalCurrencyDisplay, order.paymentMethod, order_item.sku, order_item.Name, order_item.quantity, order_item.image, order_item.TotalAmountInLocalCurrencyDisplay')
            ->asArray()
            ->all();

        $data = [];
        if($order) {
            echo $order['buyerName'];
            $data['buyerName'] = $order[0]['buyerName'];
            $data['buyerEmail'] = $order[0]['buyerEmail'];
            $data['buyerPhone'] = $order[0]['buyerPhone'];
            $data['buyerAddress'] = $order[0]['buyerAddress'];
            $data['OrderTotalInLocalCurrencyDisplay'] = $order[0]['OrderTotalInLocalCurrencyDisplay'];
            $data['paymentMethod'] = $order[0]['paymentMethod'];
            $data['items'] = [];
            foreach ($order as $order_item) {
                $data['items']['sku'] = $order_item['sku'];
                $data['items']['name'] = $order_item['Name'];
                $data['items']['quantity'] = $order_item['quantity'];
                $data['items']['image'] = $order_item['image'];
                $data['items']['TotalAmountInLocalCurrencyDisplay'] = $order_item['TotalAmountInLocalCurrencyDisplay'];
            }
        }


        return $this->response(true, "ok", $data);
    }


    public function actionDatamonth()
    {

        $store = Yii::$app->request->post('store', 1);
        date_default_timezone_set('Asia/Ho_Chi_Minh');


        if(Yii::$app->request->post('firstdate')) {
            $first_date = Yii::$app->request->post('firstdate') . " 00:00:00";
        } else {
            $first_date = date("Y-m-01 00:00:00");
        }

        if(Yii::$app->request->post('lastdate')) {
            $last_date = Yii::$app->request->post('lastdate') . " 23:59:59";
        } else {
            $last_date = date("Y-m-d 23:59:59");
        }
        $current_date = date('Y-m-d H:i:s');

        $user_test = [3645912, 3685963, 3691584, 3695739, 49467224, 49566540, 49569296, 49554964, 49554966, 49554968, 49555095, 49555892, 49564152, 49597683, 49598555, 49601582];

        $order_month = Order::find()
            ->where(['storeId' => $store])
            ->andWhere(['!=', 'type', 6])
            ->andWhere('binCode is not null')
            ->andWhere(['!=', 'remove', 1])
            ->andwhere(['or', ['between', 'createTime', $first_date, $last_date], ['between', 'LastPaidTime', $first_date, $last_date]])
            ->andwhere(['or', ['CustomerId' => null], ['not in', 'CustomerId', $user_test]])
            ->select('id,supportEmail, buyerName, buyerEmail, buyerPhone,TotalPaidAmount, buyerAddress, paymentMethod, paymentStatus, createTime, CustomerId, OrderTotalInLocalCurrencyFinal, LastPaidTime, storeId')
            ->all();

        $all_order = 0;
        $paid_order = 0;
        $id_order_paid_last = 0;

        $total_order = 1;
        $total_paid_order = 0;

        $sale[] = array();
        /**
         * @var Order $order
         */
        foreach ($order_month as $key => $order) {
            $sale_name = mb_strtoupper(str_replace('@peacesoft.net', '', str_replace('@gmail.com', '', $order->supportEmail)));

            if(!isset($sale[$sale_name])) {
                $sale[$sale_name] = [
                    'amount' => $order->OrderTotalInLocalCurrencyFinal,
                    'newCount' => 1,
                    'paidAmount' => $order->TotalPaidAmount,
                    'paidCount' => $order->LastPaidTime != null ? 1 : 0
                ];
            } else {
                $tem = $sale[$sale_name];
                $tem['amount'] += $order->OrderTotalInLocalCurrencyFinal;
                $tem['newCount'] += 1;
                if($order->LastPaidTime != null) {
                    $tem['paidAmount'] += $order->TotalPaidAmount;
                    $tem['paidCount'] += 1;
                }
                $sale[$sale_name] = $tem;
            };
            //Tong don hang
            $all_order ++;
            if($order->LastPaidTime != null) {
                //Tong don da thanh toan
                $paid_order ++;

                //Tong tien da thanh toan
                $total_paid_order += $order->OrderTotalInLocalCurrencyFinal;
            }
            if($order->LastPaidTime > $order_month[$id_order_paid_last]->LastPaidTime) {

                //id cua don thanh toan gan nhat
                $id_order_paid_last = $key;
            }
            //tong tien tat ca order
            $total_order += $order->OrderTotalInLocalCurrencyFinal;

        }
        $rs = [];
        foreach ($sale as $k => $item) {
            $r = [];
            if($k != null && $item['amount'] > 0) {
                $r['email'] = $k;
                $r['value'] = $item;
                $r['paid'] = $item['paidAmount'];
                $rs[] = $r;
            }

        }
        usort($rs, function ($a, $b) {
            return $a['paid'] < $b['paid'];
        });


        //Data for chart
        $order_chart = Order::find()
            ->where(['storeId' => $store])
            ->andWhere('binCode is not null')
            ->andWhere(['!=', 'type', 6])
            ->andWhere(['!=', 'remove', 1])
            ->andwhere(['or', ['between', 'createTime', date("Y-m-01"), date("Y-m-d 23:59:59")], ['between', 'LastPaidTime', date("Y-m-01"), date("Y-m-d 23:59:59")]])
            ->andwhere(['or', ['CustomerId' => null], ['not in', 'CustomerId', $user_test]])
            ->select('id, createTime, OrderTotalInLocalCurrencyFinal,TotalPaidAmount, LastPaidTime, storeId')->orderBy('id asc')
            ->all();
        $data_chart = [];
        $total_order_month = 0;
        $total_order_paid_month = 0;
        $begin = new DateTime(date('Y-m-01'));
        $end = new DateTime(date('Y-m-d 23:59:59'));;

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $thisDate = $i->format('Y-m-d');
            $data_chart[$thisDate] = [
                'period' => $thisDate,
                'total_order' => 0,
                'total_order_count' => 0,
                'total_paid_order' => 0,
                'total_paid_order_count' => 0,
                'convert_percent' => 0
            ];
        }
        foreach ($order_chart as $item) {
            $total_order_month += $item->OrderTotalInLocalCurrencyFinal;
            $total_order_paid_month += $item->TotalPaidAmount;
            $dateCreate = new DateTime($item->createTime);
            $dateCreate = $dateCreate->format('Y-m-d');

            $datePaid = new DateTime($item->LastPaidTime != null ? $item->LastPaidTime : date('Y-m-d'));
            $datePaid = $datePaid->format('Y-m-d');

            if(isset($data_chart[$dateCreate])) {
                $tem = $data_chart[$dateCreate];
                $tem['total_order'] += $item->OrderTotalInLocalCurrencyFinal;
                $tem['total_order_count'] += 1;
                $tem['convert_percent'] = $tem['total_order_count'] > 0 ? round($tem['total_paid_order_count'] / $tem['total_order_count'] * 100) : 0;
                $data_chart[$dateCreate] = $tem;
            }
            if(isset($data_chart[$datePaid])) {
                $tem = $data_chart[$datePaid];
                $tem['total_paid_order'] += $item->TotalPaidAmount;
                $tem['total_paid_order_count'] += $item->TotalPaidAmount > 0 ? 1 : 0;
                $tem['convert_percent'] = $tem['total_order_count'] > 0 ? round($tem['total_paid_order_count'] / $tem['total_order_count'] * 100) : 0;
                $data_chart[$datePaid] = $tem;
            }
        }
        $chart_line = [];
        foreach ($data_chart as $k => $item) {
            $chart_line[] = $item;
        }

        $last_order_paid = $order_month[$id_order_paid_last];
        $last_paid = [];
        $last_paid['name'] = $last_order_paid['buyerName'];
        $last_paid['email'] = $last_order_paid['buyerEmail'];
        $last_paid['phone'] = $last_order_paid['buyerPhone'];
        $last_paid['address'] = $last_order_paid['buyerAddress'];
        $last_paid['paid_time'] = $last_order_paid['LastPaidTime'];
        $last_paid['total'] = $last_order_paid['OrderTotalInLocalCurrencyFinal'];
        $last_paid['sale'] = mb_strtoupper(str_replace('@peacesoft.net', '', str_replace('@gmail.com', '', $last_order_paid['supportEmail'])));;
        $last_paid['has_customerId'] = false;
        if($last_order_paid['CustomerId']) {
            $last_paid['customerId'] = $last_order_paid['CustomerId'];
            $last_paid['has_customerId'] = true;
        }
        $last_paid['order_id'] = $last_order_paid['id'];

        $data['all_order'] = $all_order;
        $data['paid_order'] = $paid_order;
        $data['total_order_month'] = round($total_order_month);
        $data['total_order_paid_month'] = round($total_order_paid_month);
        $data['total_order'] = $total_order;
        $data['total_paid_order'] = $total_paid_order;
        $data['last_paid'] = $last_paid;
        $data['data_chart'] = $chart_line;
        $data['sales'] = $rs;
        return $this->response(true, "ok", $data);
    }

    public
    function actionUserlastpaid()
    {
        $customerId = Yii::$app->request->post('id');

        $customer = Customer::find()
            ->where(['id' => $customerId])
            ->select('firstName, lastName, phone, email, address')
            ->asArray()
            ->one();
        if($customer) {
            $data['name'] = $customer['firstName'] . " " . $customer['lastName'];
            $data['email'] = $customer['email'];
            $data['phone'] = $customer['phone'];
            $data['address'] = $customer['address'];
        }

        if($data) {
            return $this->response(true, "ok", $data);
        }
        return $this->response(false, "not ok", null);
    }

    public function actionOrderitemlastpaid()
    {
        $order_id = Yii::$app->request->post('order_id');

        if($order_id) {
            $list_order_item = OrderItem::find()
                ->where(['orderId' => $order_id])
                ->select('Name, sku, quantity, image, TotalAmountInLocalCurrency, sourceId, TotalAmountInLocalCurrencyDisplay')
                ->asArray()
                ->all();
        }
        if($list_order_item) {
            return $this->response(true, "ok", $list_order_item);
        }
        return $this->response(false, "not ok", null);
    }

    /**
     *
     */
    public function actionSaveReportcommand()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $post = Yii::$app->request->post();
        if(empty($post)) {
            return $this->response(false, "false", []);
        }

        $post['name'] = !empty($post['name']) ? $post['name'] : '';
        $post['typeReport'] = !empty($post['typeReport']) ? $post['typeReport'] : 'PURCHASE';
        $post['dateRange'] = explode(',', $post['dateRange']);
        $from = '';
        $to = '';
        if(!empty($post['dateRange'][0]) && !empty($post['dateRange'][1])) {
            $from = new \DateTime(substr($post['dateRange'][0], 0, strpos($post['dateRange'][0], '(')));
            $from = $from->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d');
            $to = new \DateTime(substr($post['dateRange'][1], 0, strpos($post['dateRange'][1], '(')));
            $to = $to->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d');
        }
        $post['$from'] = $from;
        $post['$to'] = $to;

        $command = new ReportCommand();
        $command->name = $post['name'];
        $command->typeReport = $post['typeReport'];
        $command->typeTime = $post['typeTime'];
        $command->storeId = $post['storeId'];
        $command->startTime = $from;
        $command->EndTime = $to;
        $command->email = $post['resendemail'];
        $command->isExtra = 0;
        $command->createdAt = date('Y-m-d H:i:s');
        $command->quantity = 0;

        if(!$command->validate()) {
            return $this->response(false, 'error', $command->getErrors());
        }

        $command->save(false);
        return $this->response(true, "ok", $post);
    }


    public function actionReportcommand()
    {

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $post = Yii::$app->request->post();
        $itemNumber = 1000;
        $listCommand = ReportCommand::find()->where(['<>', 'isDelete', '1'])->asArray()->limit(20)->orderBy(['createdAt' => SORT_DESC])->all();
        foreach ($listCommand as $key => $value) {
            $value['quantity'] = !empty($value['quantity']) ? $value['quantity'] : 1;
            $totalpage = (int)$value['quantity'] / $itemNumber;
            for ($i = 1; $i <= $totalpage + 1; $i ++) {
                $listCommand[$key]['buttonExplode'][] = $i;
            }
            if(empty($value['linkExcel'])) {
                $value['linkExcel'] = '';
            }
            $linkExcel = explode(",", $value['linkExcel']);
            $linkExcel = array_unique($linkExcel);
            sort($linkExcel);
            $listCommand[$key]['linkExcel'] = [];

            foreach ($linkExcel as $keyExcel => $valExcel) {
                if(!empty($valExcel))
                    $listCommand[$key]['linkExcel'][] = [
                        'link' => Url::base(true) . '/Files/' . TextUtility::removeUnicode($value['name'] . '_' . $valExcel, true) . '.xlsx',
                        'page' => $valExcel
                    ];
            }

        }
        $data['listCommand'] = $listCommand;
        return $this->response(true, "ok", $data);

    }


    public function actionExpoleSla()
    {
        $post = Yii::$app->request->post();
        $page = $post['page'];
        $name = $post['name'];
        $itemNumber = 1000;
        $offset = ($page - 1) * $itemNumber;
        $limit = $itemNumber;
        //todo get command
        $command = ReportCommand::find()->where(['<>', 'isDelete', '1'])->andWhere(['name' => $name])->one();
        $actualQuantity = ReportPurchaseData::find()->where(['commandName' => $name])->andWhere(['not in', 'binCode', ['', null]])->count();
        $command->actualQuantity = $actualQuantity;
        if($command->typeTime == 'ShippingCreateTime') {
            $dataFeed = ReportPurchaseData::find()->where(['commandName' => $name])->offset($offset)->limit($limit)->asArray()->all();
            $sheet1 = [
                'A1' => 'commandName',
                'B1' => 'soi',
                'C1' => 'typeTime',
                'D1' => 'binCode',
                'E1' => 'buyerName',
                'F1' => 'createTime',
                'G1' => 'buyerEmail',
                'H1' => 'buyerPhone',
                'I1' => 'buyerAddress',
                'J1' => 'billingCountryName',
                'K1' => 'OrderItemTotal',
                'L1' => 'TotalAmountInLocalCurrencyDisplay',
                'M1' => 'orderItemName',
                'N1' => 'localProductName',
                'O1' => 'link',
                'P1' => 'purchaseTrackingCode',
                'Q1' => 'localShipmentCode',
                'R1' => 'manifestCode',
                'S1' => 'exportWarehouseStockInTime',
                'T1' => 'exportWarehouseStockOutTime',
                'U1' => 'localWarehouseStockinTime',
                'V1' => 'localWarehouseStockoutTime',
                'W1' => 'status',
            ];
            $rows = [];
            $rowIndex = 1;
            foreach ($dataFeed as $key => $val) {
                $rowIndex ++;
                $rows['A' . $rowIndex] = !empty($val['commandName']) ? $val['commandName'] : '';
                $rows['B' . $rowIndex] = !empty($val['soi']) ? $val['soi'] : '';
                $rows['C' . $rowIndex] = !empty($val['typeTime']) ? $val['typeTime'] : '';
                $rows['D' . $rowIndex] = !empty($val['binCode']) ? $val['binCode'] : '';
                $rows['E' . $rowIndex] = !empty($val['buyerName']) ? $val['buyerName'] : '';
                $rows['F' . $rowIndex] = !empty($val['createTime']) ? $val['createTime'] : '';
                $rows['G' . $rowIndex] = !empty($val['buyerEmail']) ? $val['buyerEmail'] : '';
                $rows['H' . $rowIndex] = !empty($val['buyerPhone']) ? $val['buyerPhone'] : "";
                $rows['I' . $rowIndex] = !empty($val['buyerAddress']) ? $val['buyerAddress'] : '';
                $rows['J' . $rowIndex] = !empty($val['billingCountryName']) ? $val['billingCountryName'] : '';
                $rows['K' . $rowIndex] = !empty($val['OrderItemTotal']) ? $val['OrderItemTotal'] : '';
                $rows['L' . $rowIndex] = !empty($val['TotalAmountInLocalCurrencyDisplay']) ? $val['TotalAmountInLocalCurrencyDisplay'] : '';
                $rows['M' . $rowIndex] = !empty($val['orderItemName']) ? $val['orderItemName'] : '';
                $rows['N' . $rowIndex] = !empty($val['localProductName']) ? $val['localProductName'] : '';
                $rows['O' . $rowIndex] = !empty($val['link']) ? $val['link'] : '';
                $rows['P' . $rowIndex] = !empty($val['purchaseTrackingCode']) ? $val['purchaseTrackingCode'] : '';
                $rows['Q' . $rowIndex] = !empty($val['localShipmentCode']) ? $val['localShipmentCode'] : '';
                $rows['R' . $rowIndex] = !empty($val['manifestCode']) ? $val['manifestCode'] : '';
                $rows['S' . $rowIndex] = !empty($val['exportWarehouseStockInTime']) ? $val['exportWarehouseStockInTime'] : '';
                $rows['T' . $rowIndex] = !empty($val['exportWarehouseStockOutTime']) ? $val['exportWarehouseStockOutTime'] : '';
                $rows['U' . $rowIndex] = !empty($val['localWarehouseStockinTime']) ? $val['localWarehouseStockinTime'] : '';
                $rows['V' . $rowIndex] = !empty($val['localWarehouseStockoutTime']) ? $val['localWarehouseStockoutTime'] : '';
                $rows['W' . $rowIndex] = !empty($val['status']) ? $val['status'] : '';
                $sheet1 = ArrayHelper::merge($sheet1, $rows);
            }

            $action = new ExcelSpreadsheet();
            $nameFile = TextUtility::removeUnicode($name . '_' . $page, true);
            $rsurl = $action->SaveExcle($sheet1, $nameFile);
            $data['url'] = $rsurl;
            $linkExcel = explode(',', $command->linkExcel);
            if(!in_array($page, $linkExcel)) {
                $command->linkExcel .= ',' . $page;
            }
            $command->save(false);
            $data['linkExcel'] = $command->linkExcel;


        } else {
            //todo get data of command
            $dataFeed = ReportPurchaseData::find()->where(['commandName' => $name])->offset($offset)->limit($limit)->asArray()->all();
            //print_r($dataFeed);
            $sheet1 = [
                'A1' => 'commandName',
                'B1' => 'soi',
                'C1' => 'so',
                'D1' => 'binCode',
                'E1' => 'store',
                'F1' => 'manifest',
                'G1' => 'productName',
                'H1' => 'fistPaymentTime',
                'I1' => 'purchaseAssignTime',
                'K1' => 'purchaseOrderCode',
                'L1' => 'purchasePaypalAmount',
                'M1' => 'purchaseTime',
                'N1' => 'trackingCode',
                'O1' => 'sellerShipTime',
                'P1' => 'estimateDeliveryTime',
                'Q1' => 'warehouseTag',
                'R1' => 'atUSTime',
                'S1' => 'inspectTime',
                'T1' => 'stockoutUSTime',
                'U1' => 'Warehouse',
                'V1' => 'ItemType',

            ];

            $rows = [];
            $rowIndex = 1;
            foreach ($dataFeed as $key => $val) {
                $rowIndex ++;
                $rows['A' . $rowIndex] = !empty($val['commandName']) ? $val['commandName'] : '';
                $rows['B' . $rowIndex] = !empty($val['soi']) ? $val['soi'] : '';
                $rows['C' . $rowIndex] = !empty($val['so']) ? $val['so'] : '';
                $rows['D' . $rowIndex] = !empty($val['binCode']) ? $val['binCode'] : '';
                $rows['E' . $rowIndex] = !empty($val['store']) ? $val['store'] : '';
                $rows['F' . $rowIndex] = !empty($val['manifest']) ? $val['manifest'] : '';
                $rows['G' . $rowIndex] = !empty($val['productName']) ? $val['productName'] : '';
                $rows['H' . $rowIndex] = !empty($val['fistPaymentTime']) ? $val['fistPaymentTime'] : "";
                $rows['I' . $rowIndex] = !empty($val['purchaseAssignTime']) ? $val['purchaseAssignTime'] : '';
                $rows['K' . $rowIndex] = !empty($val['purchaseOrderCode']) ? $val['purchaseOrderCode'] : '';
                $rows['L' . $rowIndex] = !empty($val['purchasePaypalAmount']) ? $val['purchasePaypalAmount'] : '';
                $rows['M' . $rowIndex] = !empty($val['purchaseTime']) ? $val['purchaseTime'] : '';
                $rows['N' . $rowIndex] = !empty($val['trackingCode']) ? $val['trackingCode'] : '';
                $rows['O' . $rowIndex] = !empty($val['sellerShipTime']) ? $val['sellerShipTime'] : '';
                $rows['P' . $rowIndex] = !empty($val['estimateDeliveryTime']) ? $val['estimateDeliveryTime'] : '';
                $rows['Q' . $rowIndex] = !empty($val['warehouseTag']) ? $val['warehouseTag'] : '';
                $rows['R' . $rowIndex] = !empty($val['atUSTime']) ? $val['atUSTime'] : '';
                $rows['S' . $rowIndex] = !empty($val['inspectTime']) ? $val['inspectTime'] : '';
                $rows['T' . $rowIndex] = !empty($val['stockoutUSTime']) ? $val['stockoutUSTime'] : '';
                $rows['U' . $rowIndex] = !empty($val['note']) ? $val['note'] : '';
                $rows['V' . $rowIndex] = !empty($val['itemType']) ? $val['itemType'] : '';
                $sheet1 = ArrayHelper::merge($sheet1, $rows);
            }

            $action = new ExcelSpreadsheet();
            $nameFile = TextUtility::removeUnicode($name . '_' . $page, true);
            $rsurl = $action->SaveExcle($sheet1, $nameFile);
            $data['url'] = $rsurl;
            $linkExcel = explode(',', $command->linkExcel);
            if(!in_array($page, $linkExcel)) {
                $command->linkExcel .= ',' . $page;
            }
            $command->save(false);
            $data['linkExcel'] = $command->linkExcel;
        }

        return $this->response(true, "ok", $data);


    }


}