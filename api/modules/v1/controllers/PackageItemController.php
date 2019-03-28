<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-20
 * Time: 09:29
 */

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use common\data\ActiveDataProvider;
use common\models\PackageItem;
use Yii;

class PackageItemController extends BaseApiController
{

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET']
        ];
    }

    /**
     * @inheritdoc
     */
    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getQueryParams();
        $query = PackageItem::find();

        $query->filterRelation();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeParam' => 'perPage',
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);

        $query->filter($requestParams);

        return $this->response(true, 'get data success', $dataProvider);
    }

    public function actionUpdate($id) {
        $post = Yii::$app->request->post();

        $model = PackageItem::findOne($id);
        if ($model) {
            $model->package_code = $post['package_code'];
            $model->weight = $post['weight'];
            $model->dimension_l = $post['dimension_l'];
            $model->dimension_h = $post['dimension_h'];
            $model->dimension_w = $post['dimension_w'];
            $model->save();
            return $this->response(true, 'update package' . $id .'item success', $model);
        }
    }
}