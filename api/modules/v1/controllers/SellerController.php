<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/26/2019
 * Time: 3:11 PM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\Seller;

class SellerController extends BaseApiController
{
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

    public function actionIndex() {
        $request = Seller::find()
            ->select([
            'id',
            'seller_name'
        ])
            ->asArray()->all();
        return $this->response(true, 'Success', $request);
    }
}