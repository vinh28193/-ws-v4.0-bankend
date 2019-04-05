<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/3/2019
 * Time: 6:24 PM
 */

namespace api\modules\v1\controllers;


use api\controllers\BaseApiController;
use common\models\db\ListAccountPurchase;

class ExtensionController extends BaseApiController
{
    public function actonListAccount() {
        $model = ListAccountPurchase::find()->asArray()->all();
        return $this->response(true, 'success', $model);
    }

}