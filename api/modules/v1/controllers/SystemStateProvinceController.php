<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/23/2019
 * Time: 10:19 AM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use Yii;

class SystemStateProvinceController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['pageCache'] = [
//            'class' => 'yii\filters\PageCache',
//            'only' => ['index'],
//            'duration' => 24 * 3600 * 365, // 1 year
//            'dependency' => [
//                'class' => 'yii\caching\ChainedDependency',
//                'dependencies' => [
//                    new DbDependency(['sql' => 'SELECT MAX(id) FROM ' . Order::tableName()])
//                ]
//            ],
//        ];
        return $behaviors;
    }


    protected function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update'],
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
            'delete' => ['DELETE']
        ];
    }

    public function actionIndex($country = null) {
        $cache = Yii::$app->cache;
        $cacheKey = 'SAVE_CACHE_C_' . $country;
        if (($data = $cache->get($cacheKey)) === false) {
            $query = \common\models\db\SystemStateProvince::find()->with('systemDistricts');
            if ($country !== 'all') {
                $query->andWhere(['country_id' => $country]);
            }
            $data = $query->asArray()->all();
            $cache->set($cacheKey, $data, 3600);
        }
        Yii::$app->response->format = 'json';
        return $data;
    }

}