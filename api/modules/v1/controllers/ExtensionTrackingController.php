<?php


namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\components\db\ActiveQuery;
use common\models\draft\DraftExtensionTrackingMap;

class ExtensionTrackingController extends BaseApiController
{
    /** @var ActiveQuery $query */
    public $query;
    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true)
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['POST'],
            'update' => ['PUT'],
            'create' => ['POST']
        ];
    }

    public function actionIndex(){

        $this->query = DraftExtensionTrackingMap::find();
        $this->setJoinWith();
        $this->setFilter();
        $data['total'] = $this->query->count();
        $this->query->limit($this->post['limit'])->offset($this->post['page']*$this->post['limit'] - $this->post['limit']);
        $model = $this->query->orderBy('id desc')->asArray()->all();
        $data['data'] = $model;
        $data['totalPage'] = ceil($data['total']/$this->post['limit']);
        $data['page'] = $this->post['page'];
        return $this->response(true,'Get success',$data);
    }

    public function actionUpdate(){

    }

    public function actionCreate(){
        print_r($this->post);
        die;
    }

    public function setFilter(){
        $filter = $this->post['filter'];
        $this->query->leftJoin('order','order.id = draft_extension_tracking_map.order_id');
        $this->query->leftJoin('product','product.id = draft_extension_tracking_map.product_id');
        $this->query->leftJoin('purchase_order','purchase_order.id = draft_extension_tracking_map.purchase_invoice_number');
        if($filter['orderCode']){
            $this->query->andWhere(['like','order.ordercode' , $filter['orderCode']]);
        }
        if($filter['orderId']){
            $this->query->andWhere(['like','draft_extension_tracking_map.order_id' , $filter['orderId']]);
        }
        if($filter['trackingCode']){
            $this->query->andWhere(['like','draft_extension_tracking_map.tracking_code' , $filter['trackingCode']]);
        }
        if($filter['sku']){
            $this->query->andWhere(['or',['like','product.sku' ,$filter['sku']],['like','product.parent_sku' ,$filter['sku']]]);
        }
        if($filter['purchaseInvoice']){
            $this->query->andWhere(['or',['like','purchase_order.id',$filter['purchaseInvoice']],['like','purchase_order.purchase_order_number',$filter['purchaseInvoice']]]);
        }
    }
    public function setJoinWith(){
        $this->query->with(['order','product','purchase']);
    }
}