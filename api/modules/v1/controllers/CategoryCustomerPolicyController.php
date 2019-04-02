<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/2/2019
 * Time: 1:28 PM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\CategoryCustomPolicy;
use Yii;

class CategoryCustomerPolicyController extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
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

    public function actionView($id) {
        $model = CategoryCustomPolicy::find()
        ->where(['store_id' => $id])
        ->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }
}