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
use common\models\db\CategoryGroup;
use Yii;
use yii\caching\DbDependency;
class CategoryCustomerPolicyController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['pageCache'] = [
            'class' => 'yii\filters\PageCache',
            'only' => ['index'],
            'duration' => 24 * 3600 * 365, // 1 year
            'dependency' => [
                'class' => 'yii\caching\ChainedDependency',
                'dependencies' => [
                    new DbDependency(['sql' => 'SELECT MAX(id) FROM ' . CategoryCustomPolicy::tableName()])
                ]
            ],
        ];
        return $behaviors;
    }

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
                'actions' => ['index', 'view', 'update'],
                'roles' => $this->getAllRoles(true)
            ],
        ];
    }

    public function actionView($id) {
        if ($id) {
            $model = CategoryGroup::find()
                ->where(['store_id' => $id])
                ->asArray()->all();
            return $this->response(true, 'get data success', $model);
        }
        return $this->response(false, 'error');
    }

    public function actionIndex() {
        $model = CategoryGroup::find()->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }
}
