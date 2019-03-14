<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 13:02
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\Package;
use yii\helpers\ArrayHelper;

class PackageController extends BaseApiController
{

    public function verbs()
    {
        return ArrayHelper::merge(parent::verbs(), [
            'index' => ['GET']
        ]);
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
//        $requestParams = Yii::$app->getRequest()->getQueryParam();
        $query = Package::find();

        $query->joinWith(['packageItems' => function (\yii\db\ActiveQuery $q) {
            $q->with(['order' => function(\common\models\queries\OrderQuery $orderQuery){
                    $orderQuery->with('products');
            } ]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->response(true, 'get data success', $dataProvider);
    }
}