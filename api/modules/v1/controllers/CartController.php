<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 17:24
 */

namespace api\modules\v1\controllers;

use backend\models\CartAddForm;
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
        $queryParam = Yii::$app->request->queryParams;
        return $this->response(true, 'get cart success', new ArrayDataProvider([
            'allModels' => $this->getCart()->filterShoppingCarts($queryParam),
        ]));

    }

    public function actionCreate()
    {
        Yii::info($this->post,'POST');
        if (($key = $this->getCart()->addItem($this->post)) === false) {
            $this->response(false, 'can not add item');
        };
        return $this->response(true, "ok", $this->getCart()->getItem($key));
    }

    public function actionUpdate($code) {
        $post = Yii::$app->request->post();
        if (($key = $this->getCart()->updateSafeItem(($post['type']), $code, $post)) === false) {
            $this->response(false, 'can not update item');
        }
        return $this->response(true, "ok", $this->getCart()->updateSafeItem(($post['type']), $code, $post));
    }
    /**
     * @return \common\components\cart\CartManager
     */
    protected function getCart()
    {
        return Yii::$app->cart;
    }

    protected function resolveChatMessage($dirtyAttributes, $reference)
    {

        $results = [];
        foreach ($dirtyAttributes as $name => $value) {
            if (strpos($name, '_id') !== false && is_numeric($value)) {
                continue;
            }
            $results[] = "`{$reference->getAttributeLabel($name)}` changed from `{$reference->getOldAttribute($name)}` to `$value`";
        }

        return implode(", ", $results);
    }
}
