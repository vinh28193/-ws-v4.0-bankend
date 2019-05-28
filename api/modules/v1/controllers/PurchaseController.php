<?php
namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\Customer;
use common\models\db\ListAccountPurchase;
use common\models\db\PurchaseOrder;
use common\models\db\PurchasePaymentCard;
use common\models\PurchaseProduct;
use common\models\Order;
use common\models\Product;
use common\models\User;
use common\models\Warehouse;
use common\models\weshop\FormPurchaseItem;
use common\models\weshop\FormRequestPurchase;
use Yii;
use yii\db\ActiveQuery;

class PurchaseController extends BaseApiController
{
    public $post;
    public $get;
    public $session;
    public function init()
    {
        $this->post = \Yii::$app->request->post();
        $this->get = \Yii::$app->request->get();
        parent::init(); // TODO: Change the autogenerated stub
    }

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update','delete'],
                'roles' => $this->getAllRoles(true),

            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => $this->getAllRoles(true, 'user'),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, 'user'),
            ],
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'get-list-account' => ['GET'],
            'get-list-card-payment' => ['GET']
        ];
    }

    public function actionIndex(){
        die("Action Test");
    }

    /**
     *Add cart purchase .
     */
    public function actionUpdate(){
        if(isset($this->get['id'])){
            $listId = [$this->get['id']];
            $repon = $this->getCart($listId);
            return $repon;
        }
        return $this->response(false,"not have Id");
    }

    public function actionDelete($id){
            /** @var Order $order */
            $order = Order::find()->where( [
                'purchase_assignee_id'=>Yii::$app->user->getId(),
                'current_status' => Order::STATUS_PURCHASING,
                'id' => $id
            ])->limit(1)->one();
            $order->current_status = $order->total_purchase_quantity == 0 ?  Order::STATUS_READY2PURCHASE : Order::STATUS_PURCHASE_PART;
            $order->purchase_assignee_id = $order->total_purchase_quantity == 0 ?  null : $order->purchase_assignee_id;
            $order->save(0);
//            $item = Order::find()->where(['id' => $this->post['idItem']])->limit(1)->one();
//            $mess = "Remove soi ".$this->post['idItem']." to cart. ";
//            OrderLogs::log($item->id,"action",$mess,Yii::$app->user->getIdentity()->username,$mess,$item->id);
        $repon = $this->getCart([],'Remove Success!');
        Yii::$app->wsLog->push('order','removeItemToCart', null, [
            'id' => $id,
            'request' => "delete/".$this->id,
            'response' => $repon
        ]);
            return $repon;
    }

    function getCart($listId = [],$message = ''){
        $type = Yii::$app->request->post('type','buynow');
        $data = [];
        $listId_cancel = [];
        $mess = '';
        $success = false;
        $addfee_amount = 0;
        /** @var Order[] $orders */
        if($listId && $type == 'addtocart'){
            $orders = Order::find()->with(['products'])
                ->where(['id'=>$listId,'current_status' => [Order::STATUS_READY2PURCHASE,Order::STATUS_PURCHASE_PART]])
                ->orWhere(['purchase_assignee_id'=>Yii::$app->user->getId(),'current_status' => Order::STATUS_PURCHASING])
                ->limit(1)->all();
        }elseif($listId){
            $orders = Order::find()->with('products')
                ->where([
                    'id'=>$listId,
                    'current_status' => [Order::STATUS_READY2PURCHASE,Order::STATUS_PURCHASE_PART,Order::STATUS_PURCHASING]
                ])
                ->limit(1)->all();
        }else{
            return $this->response(false,"Cart emty",[]);
//            $success = true;
//            $orders = Order::find()->with('products')
//                ->where(['purchase_assignee_id'=>Yii::$app->user->getId(),'current_status' => Order::STATUS_PURCHASING])
//                ->limit(1)->all();
        }
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        foreach ($orders as $key => $order){
            $data[$key]['order_id'] = $order->id;
            $data[$key]['ordercode'] = $order->ordercode;
            $data[$key]['seller'] = $order->seller_name;
            $data[$key]['total_amount'] = $order->total_final_amount_local;
            $data[$key]['portal'] = $order->portal;
            foreach ($order->products as $item){
                if($item->quantity_customer > $item->quantity_purchase) {
                    $addfee_amount = 0;
                    $item->purchase_start = $item->purchase_start ? $item->purchase_start : time();
                    $item->current_status = Product::STATUS_PURCHASING;
                    $item->save(0);
//                /** @var OrderPaymentRequest[] $order_request_change */
//                $order_request_change = OrderPaymentRequest::find()
//                    ->where([
//                        'reason'=>'INCREASE PRICE',
//                        'order_item_id'=> $item->id,
//                        'type' => 'ADDFEE',
//                        'status'=>[
//                            OrderPaymentRequest::STATUS_APPROVED,
//                            OrderPaymentRequest::STATUS_REQUESTED,
//                            OrderPaymentRequest::STATUS_COMPLETED,
//                        ]
//                    ])->all();
//                foreach ($order_request_change as $addfee){
//                    $addfee_amount += $addfee->weshop_fee ? floatval($addfee->amount) - floatval($addfee->weshop_fee) : floatval($addfee->amount);
//                }
                    /** @var PurchaseProduct[] $list_purchased */
                    $list_purchased = PurchaseProduct::find()->where(['product_id' => $item->id])->all();
                    $amount_purchased = 0;
                    $quantity_purchased = 0;
                    foreach ($list_purchased as $purchaseOrderItem) {
                        $quantity_purchased += $purchaseOrderItem->purchase_quantity;
                        $amount_purchased += $purchaseOrderItem->paid_to_seller;
                    }
                    /** @var Customer $cus */
                    $cus = $order->customer_id ? Customer::findOne($order->customer_id) : false;
                    $tmp = new FormPurchaseItem();
                    $tmp->id = $item->id;
                    $tmp->order_id = $order->id;
                    $tmp->condition = $item->condition;
                    $tmp->sellerId = $order->seller_name;
                    $tmp->ItemType = $order->portal;
                    $tmp->image = $item->link_img;
                    $tmp->Name = $item->product_name;
                    $tmp->typeCustomer = $cus && $cus->type_customer ? $cus->type_customer : 1;
                    $tmp->price = $item->unitPrice ? $item->unitPrice->amount : 0;
                    $tmp->price_purchase = $item->price_purchase ? $item->price_purchase : $tmp->price;
                    $tmp->us_ship = $item->usShippingFee ? $item->usShippingFee->amount : 0;
                    $tmp->us_ship_purchase = $item->shipping_fee_purchase ? $item->shipping_fee_purchase : $tmp->us_ship;
                    $tmp->us_tax = $item->usTax ? $item->usTax->amount : 0;
                    $tmp->us_tax_purchase = $item->tax_fee_purchase ? $item->tax_fee_purchase : $tmp->us_tax;
                    $tmp->ParentSku = $item->parent_sku;
                    $tmp->sku = $item->sku;
                    $tmp->quantity = $item->quantity_customer - $quantity_purchased;
                    $tmp->quantityPurchase = $item->quantity_customer - $quantity_purchased;
                    $tmp->variation = $item->variations;
                    $tmp->paidTotal = $tmp->paidToSeller = ($tmp->price + $tmp->us_ship + $tmp->us_tax) * $tmp->quantity ;
                    $tmp->isChange = $item->tax_fee_purchase || $item->shipping_fee_purchase || $item->price_purchase;
                    $data[$key]['products'][] = $tmp->toArray();
                    if ($type == 'addtocart') {
//                    $mess = "Add soi ".$item->id." to cart. Total ".count($order)." items!";
//                    OrderLogs::log($item->orderId,"action",$mess,$user->username,$mess,$item->id);
                    }
                    $success = true;
                }else{
                    $listId_cancel[] = $item->id;
                }
            }
            if($order->current_status == Order::STATUS_READY2PURCHASE){
                $order->purchase_start = time();
                Yii::$app->wsLog->push('order','addToCart', null, [
                    'id' => $order->id,
                    'request' => "Change status to Purchasing",
                    'response' => "Success"
                ]);
            }
            $order->purchase_assignee_id = $user->id;
            $order->current_status = Order::STATUS_PURCHASING;
            $order->save(0);
        }

        if(!$success){
            $mess .="can not find anything soi in list id";
        }else{
            $mess .= "Add to cart success. ";
        }
        if(count($listId_cancel) > 0){
            $mess .="And this is list soi cancel add to card: ".implode(' ,',$listId_cancel);
            $success = true;
        }
        return $this->response($success,$message ? $message : $mess,$data);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionCreate(){
        $form = new FormRequestPurchase();
        $form->setAttributes($this->post,false);
        if(!isset($this->post['cart']) || !$this->post['cart']){
            return $this->response(false,"Nothing in your cart");
        }
        $products = [];
        foreach ($this->post['cart'] as $itemCart){
            if(isset($itemCart['products']) && $itemCart['products']){
                foreach ($itemCart['products'] as $product){
                    $newItem = new FormPurchaseItem();
                    $newItem->setAttributes($product,false);
                    $products[] = $newItem;
                }
            }
        }
        $form->products = $products;
        if(!$form || !$form->products){
            return $this->response(false,'Item or TotalPurchase was null');
        }
        $form->accountPurchase = str_replace(' ','',$form->accountPurchase);
        $form->orderIdPurchase = str_replace(' ','',$form->orderIdPurchase);
        $form->PPTranId = str_replace(' ','',$form->PPTranId);
        if(!$form->validate()){
            $mss = "";
            foreach ($form->errors as $error){
                $mss .= $error[0]." ";
            }
            return $this->response(false,$mss,$form);
        }
        $item_type = strtolower($form->products[0]->ItemType);
        $account = ListAccountPurchase::find()->where(['like','email',$form->accountPurchase])
            ->andWhere(['like','type',$item_type])->one();
        if(!$account){
            $account = new ListAccountPurchase();
            $account->account = $form->accountPurchase;
            $account->email = $form->accountPurchase;
            $account->active = 1;
            $account->type = $item_type;
            $account->save(0);
        }
        $warehouse = Warehouse::findOne($form->warehouse);
        if(!$warehouse){
            return $this->response(false,"Không tìm thấy kho tương ứng",$form);
        }
        $tran = Yii::$app->db->beginTransaction();
        try{
            $PurchaseOrder = new PurchaseOrder();
            $PurchaseOrder->note = $form->note;
            $PurchaseOrder->purchase_order_number = $form->orderIdPurchase;
            $PurchaseOrder->total_quantity = 0;
            $PurchaseOrder->total_changing_price = 0;
            $PurchaseOrder->total_item = 0;
            $PurchaseOrder->total_paid_seller = 0;
            $PurchaseOrder->total_type_changing = 0;
            $PurchaseOrder->receive_warehouse_id = $warehouse->id;
            $PurchaseOrder->purchase_account_id = $account->id;

            $card = PurchasePaymentCard::findOne($form->card_payment);

            $PurchaseOrder->purchase_card_id = $form->card_payment;
            $PurchaseOrder->purchase_card_number = $card ? $card->card_code : null;
            $PurchaseOrder->purchase_amount_buck = $form->buckAmount;
            $PurchaseOrder->transaction_payment = $form->PPTranId;
            $PurchaseOrder->note = $form->note;

            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $PurchaseOrder->updated_by = $user->id;
            $PurchaseOrder->created_at = time();
            $PurchaseOrder->updated_at = time();
            $PurchaseOrder->save(0);
            $shippingFee = 0;
            $storeId = 1;
            $changeAmount = 0;
            foreach ($form->products as $product){
                /** @var Product $item */
                $item = Product::find()->with('order')->where(['id' => $product->id])->one();
                if(!$item){
                    continue;
                }
                $amount = $product->paidToSeller - $product->paidTotal;
                $changeAmount += $amount;
                $purchaseProd = new PurchaseProduct();
                $purchaseProd->product_id = $product->id;
                $purchaseProd->purchase_order_id = $PurchaseOrder->id;
                $purchaseProd->order_id = $item->order_id;
                $purchaseProd->sku = $product->sku;
                $purchaseProd->product_name = $product->Name;
                $purchaseProd->image = $product->image;
                $purchaseProd->purchase_quantity = $product->quantityPurchase;
                $purchaseProd->receive_quantity = 0;
                $purchaseProd->receive_warehouse_id = $warehouse->id;
                $purchaseProd->receive_warehouse_name = $warehouse->name;
                $purchaseProd->paid_to_seller = $product->paidToSeller;
                $purchaseProd->changing_price = abs($amount);
                $purchaseProd->type_changing = $amount > 0 ? 'up' : 'down' ;
                $purchaseProd->purchase_price = $product->price_purchase;
                $purchaseProd->purchase_us_tax = $product->us_tax_purchase;
                $purchaseProd->purchase_shipping_fee = $product->us_ship_purchase;
                $purchaseProd->created_at = time();
                $purchaseProd->updated_at = time();
                $purchaseProd->save(0);
                $item->updated_at = time();
                $item->quantity_purchase = $item->quantity_purchase ? $item->quantity_purchase + $product->quantityPurchase : $product->quantityPurchase;
                $item->purchased = $item->purchased ? $item->purchased : time();
                $item->current_status = $item->quantity_purchase < $item->quantity_customer ? Product::STATUS_PURCHASE_PART : Product::STATUS_PURCHASED;
                $item->save(0);
                $item->order->updated_at = time();
                $item->order->purchase_order_id = $item->order->purchase_order_id ? $item->order->purchase_order_id .",".$PurchaseOrder->id : $PurchaseOrder->id ;
                $item->order->purchase_transaction_id = $item->order->purchase_transaction_id ? $item->order->purchase_transaction_id .",".$PurchaseOrder->transaction_payment : $PurchaseOrder->transaction_payment;
                $item->order->purchase_amount = $item->order->purchase_amount ? $item->order->purchase_amount + $product->paidToSeller : $product->paidToSeller;
                $item->order->purchase_account_id = $item->order->purchase_account_id ? $item->order->purchase_account_id .",". $PurchaseOrder->purchase_account_id : $PurchaseOrder->purchase_account_id;
                $item->order->purchase_card = $item->order->purchase_card ? $item->order->purchase_card .",". $PurchaseOrder->purchase_card_number : $PurchaseOrder->purchase_card_number;
                $item->order->purchase_amount_buck = $item->order->purchase_amount_buck ? $item->order->purchase_amount_buck + $PurchaseOrder->purchase_amount_buck : $PurchaseOrder->purchase_amount_buck;
                $item->order->total_purchase_quantity = $item->order->total_purchase_quantity ? $item->order->total_purchase_quantity + $product->quantityPurchase : $product->quantityPurchase;
                $item->order->purchased = $item->order->total_purchase_quantity < $item->order->total_quantity ? $item->order->purchased : time();
                $item->order->current_status = $item->order->purchased ? Order::STATUS_PURCHASED : Order::STATUS_PURCHASE_PART;
                $item->order->save(0);
                $PurchaseOrder->total_quantity += $product->quantityPurchase;
                $PurchaseOrder->total_item += 1;
                $PurchaseOrder->total_paid_seller += $product->paidToSeller;
            }
            $PurchaseOrder->total_changing_price = abs($changeAmount);
            $PurchaseOrder->total_type_changing = $changeAmount > 0 ? 'up' : 'down';
            $PurchaseOrder->save(0);



            Yii::$app->wsLog->push('order','purchased', null, [
                'id' => $PurchaseOrder->id,
                'request' => $this->post['cart'],
                'response' => $this->response(true,'Purchase success! PO-'.$PurchaseOrder->id)
            ]);

            // ToDo : @Phuchc Notication "Mua Hàng Thành Công" 17/05/2019 Call API
            Yii::$app->wsFcnApn->Create($_to_token_fingerprint = 'f42w7MQMVIU:APA91bFSWXH6PLBNgvZTXIS2gm4_QM3Lc-El46dokbqJXXtY8zv8oMaNd4B8LYOTgILSl38COdPQRY_ajdUJoecy6jSxy7O6CUOATTHMt9NqGZRu-W1018mvLzJaf4Cj1z2lSt38o5gG', $_title = 'Purchase success! PO-'.$PurchaseOrder->id , $_body = 'Purchase success! PO-'.$PurchaseOrder->id, $_click_action='https://admin.weshop.asia');

            $tran->commit();
            return $this->response(true,'Purchase success! PO-'.$PurchaseOrder->id);
        }catch (\Exception $exception){
            $tran->rollBack();
            Yii::error($exception);
            return $this->response(false,'something error');
        }
    }
    public function actionView($id){
        $purchase = PurchaseProduct::find()->where(['order_id' => $id])
            ->with([
                'purchaseOrder' => function($q) {
                /** @var ActiveQuery $q */
                    $q->with(['draftExtensionTrackingMap']);
                },
                'product'
            ])
            ->asArray()->all();
        return $this->response(true,'Success',$purchase);
    }
}

