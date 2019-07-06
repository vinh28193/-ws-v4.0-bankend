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
use common\modelsMongo\ShoppingCartUpgrade;
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
        $params = Yii::$app->request->get();
        $model = ShoppingCartUpgrade::find();
        if (isset($params['value']) && !isset($params['keyword'])) {
            $model->andWhere(
                ['OR',
                    ['LIKE', 'key.buyer.buyer_email', $params['value']],
                    ['value.ordercode', $params['value']],
                    ['LIKE', 'key.buyer.buyer_phone', $params['value']],
                ]
            );
        }
        if (isset($params['statusShopping']) && !empty($params['statusShopping'])) {
            $model->andWhere(['value.current_status' => $params['statusShopping']]);
        }

        if (isset($params['typePotential']) && !empty($params['typePotential'])) {
            $model->andWhere(['type' => $params['typePotential']]);
        }

        if (isset($params['portal']) && !empty($params['portal'])) {
            $model->andWhere(['value.portal' => $params['portal']]);
        }

        if (isset($params['store']) && !empty($params['store'])) {
            $model->andWhere(['value.store_id' => (int)$params['store']]);
        }

        if (isset($params['saleID']) && !empty($params['saleID'])) {
            $model->andWhere(['value.sale_support_id' => $params['saleID']]);
        }
        if (isset($params['potential']) && !empty($params['potential'])) {
            $model->andWhere(['value.potential' => (int)$params['potential']]);
        }
        if (isset($params['value']) && isset($params['keyword'])) {
            $model->andWhere([$params['keyword'] => $params['value']]);
        }
        if (isset($params['timeKey']) && isset($params['startTime']) && $params['endTime']) {
            $start = (Yii::$app->formatter->asTimestamp($params['startTime']));
            $end = (Yii::$app->formatter->asTimestamp($params['endTime']));
            $model->andWhere(['between', $params['timeKey'], $start, $end]);
        }
        if (!isset($params['timeKey']) && isset($params['startTime']) && $params['endTime']) {
            $start = (Yii::$app->formatter->asTimestamp($params['startTime']));
            $end = (Yii::$app->formatter->asTimestamp($params['endTime']));
            $model->andWhere(['OR',
                ['between', 'value.new', $start, $end],
                ['between', 'value.supporting', $start, $end],
                ['between', 'value.supported', $start, $end],
                ['between', 'value.cancelled', $start, $end],
            ]);
        }
        $total = $model->count();
        $limit = isset($params['limit']) ? (int)$params['limit'] : 20;
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $offset = ($page - 1) * $limit;
        $model->orderBy(['create_at' => SORT_DESC])->limit($limit)->offset($offset);
        $query = $model->asArray()->all();
        $data = [
            'model' => $query,
            'total' => $total
        ];
        return $this->response(true, 'success', $data);
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
