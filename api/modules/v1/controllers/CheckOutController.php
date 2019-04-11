<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-08
 * Time: 13:07
 */

namespace api\modules\v1\controllers;

use Yii;
use api\controllers\BaseApiController;
use api\modules\v1\models\CheckOutForm;
use common\components\cart\CartManager;

class CheckOutController extends BaseApiController
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
            'create' => ['POST']
        ];
    }

    public function actionIndex()
    {

    }

    public function actionCreate()
    {

        $form = new CheckOutForm();
        if (!$form->load($this->post)) {
            return $this->response(false, 'can not load params');
        }
        if (($res = $form->checkout()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, 'checkout success', $res);
    }
}
