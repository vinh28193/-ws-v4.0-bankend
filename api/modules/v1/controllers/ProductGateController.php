<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:44
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\products\forms\ProductDetailFrom;
use common\products\BaseProduct;
use common\products\forms\ProductSearchForm;
use Yii;

class ProductGateController extends BaseApiController
{

    /**
     * @return array
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
     * @return array
     */
    public function verbs()
    {
        return [
            'detail' => ['GET','POST'],
            'search' => ['GET','POST'],
            'calculator' => ['GET','POST']
        ];
    }

    public function actionDetail()
    {
        $paramRequests = Yii::$app->getRequest()->getBodyParams();
        $form = new ProductDetailFrom();
        $form->load($paramRequests, '');
        /** @var $product false | BaseProduct */
        if (($product = $form->detail()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, "get detail success", $product);
    }

    public function actionSearch()
    {
        $paramRequests = Yii::$app->getRequest()->getBodyParams();
        $form = new ProductSearchForm();
        $form->load($paramRequests, '');
        if (($response = $form->search()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, "success", $response);
    }

    public function actionCalculator(){
        $paramRequests = Yii::$app->getRequest()->getQueryParam();
        $form = new ProductDetailFrom();
        $form->load($paramRequests, '');
        /** @var $product false | BaseProduct */
        if (($product= $form->detail()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        $data = [
            'type' => $product->type,
            ''
        ];
        return $this->response(true, "get detail success", $res->getAdditionalFees());
    }
}