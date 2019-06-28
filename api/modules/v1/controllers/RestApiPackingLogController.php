<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 6/27/2019
 * Time: 5:28 PM
 */

namespace api\modules\v1\controllers;

use Yii;
use common\modelsMongo\PackingLogs;
use api\controllers\BaseApiController;

class RestApiPackingLogController extends BaseApiController
{
    /** Role :
    case 'cms':
    case 'warehouse':
    case 'operation':
    case 'sale':
    case 'master_sale':
    case 'master_operation':
    case 'superAdmin' :
     **/
    public function rules()
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
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canCreate']
            ],
            [
                'allow' => true,
                'actions' => ['update', 'delete'],
                'roles' => $this->getAllRoles(true, ['user','cms', 'warehouse' ,'operation','master_sale','master_operation']),
            ],
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
            'update' => ['PATCH', 'PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'group-viewed' => ['POST'],
            'customer-viewed' => ['POST']
        ];
    }

    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $query = PackingLogs::find();
        if (isset($get['tracking_code'])) {
            $query->andWhere(['more_data.tracking_code' => $get['tracking_code']]);
        }
        if (isset($get['content'])) {
            $query->andWhere(['like', 'message_log', $get['content']]);
        }
        if (isset($get['ip'])) {
            $query->andWhere(['request_ip' => $get['ip']]);
        }
        if (isset($get['user_name'])) {
            $query->andWhere(['user_name' => $get['user_name']]);
        }
        if ((isset($get['startTime']) && isset($get['endTime']))) {
            $query->andWhere(['between', 'created_at', (int)Yii::$app->formatter->asTimestamp($get['startTime']), (int)Yii::$app->formatter->asTimestamp($get['endTime'])]);
        }
        if ($get['valueCreate'] == 0) {
            $model = $query->orderBy(['created_at' => SORT_DESC]);
        }
        if ($get['valueCreate'] == 1) {
            $model = $query->orderBy(['created_at' => SORT_ASC]);
        }
        $total = $model->count();
        $limit = isset($get['limit']) ? $get['limit'] : 10;
        $page = isset($get['page']) ? $get['page'] : 1;
        $offset = ($page - 1) * $limit;
        $model->limit($limit)->offset($offset);
        $query = $model->asArray()->all();
        $data = [
            'model' => $query,
            'totalCount' => $total
        ];
        return $this->response(true, "success", $data);
    }
}