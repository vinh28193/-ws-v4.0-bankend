<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 01/04/2019
 * Time: 3:46 CH
 */

namespace api\modules\v1\controllers\service;


use api\controllers\BaseApiController;
use common\models\Warehouse;

class WarehouseServiceController extends BaseApiController
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
                'actions' => ['list'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canView']
            ],
        ];
    }

    public function verbs()
    {
        return [
            'list' => ['GET']
        ];
    }

    public function actionList(){
        $list = Warehouse::find()->where(['<>','version','0'])->andWhere(['warehouse_group' => Warehouse::GROUP_WAREHOUSE_NOTE_PURCHASE])->asArray()->all();
        return $this->response($list?true:false,'Get list warehouse success!',$list);
    }
}
