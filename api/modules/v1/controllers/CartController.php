<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 17:24
 */

namespace api\modules\v1\controllers;

use common\components\cart\storage\MongodbCartStorage;
use common\models\User;
use Yii;
use yii\data\ArrayDataProvider;
use api\controllers\BaseApiController;
use yii\helpers\ArrayHelper;

class CartController extends BaseApiController
{


    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true)
            ]
        ];
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'create' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();
//        var_dump($get);
//        die();
        if (isset($get)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->getCart()->getCartItems($get),
            ]);
        } else {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->getCart()->getCartItems($get['limit'], $get['page'], null),
            ]);
        }
        return $this->response(true, 'get cart success', $dataProvider);

    }

    public function actionCreate()
    {
        Yii::info($this->post,'POST');
        if (($key = $this->getCart()->addItem($this->post)) === false) {
            $this->response(false, 'can not add item');
        };
        return $this->response(true, "ok", $this->getCart()->getItem($key));
    }

    /**
     * @return \common\components\cart\CartManager
     */
    protected function getCart()
    {
        return Yii::$app->cart;
    }
}
