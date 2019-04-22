<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 4/9/2019
 * Time: 11:55 AM
 */

namespace api\modules\v1\controllers;


use common\models\db\Promotion;
use api\controllers\BaseApiController;
use common\promotion\PromotionForm;
use Yii;
use common\helpers\ChatHelper;

class PromotionController  extends BaseApiController
{
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'check' => ['POST']
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

    public function actionIndex() {
        $model = Promotion::find()->asArray()->all();
        return $this->response(true, 'get data success', $model);
    }

    public function actionCheck(){
        $form = new PromotionForm();
        $form->loadParam($this->post);
        return $this->response(true,'call check promotion success',$form->checkPromotion());
    }
}