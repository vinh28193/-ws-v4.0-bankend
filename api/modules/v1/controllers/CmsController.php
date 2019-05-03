<?php


namespace api\modules\v1\controllers;

use Yii;
use common\models\cms\PageForm;
use api\controllers\BaseApiController;

class CmsController extends BaseApiController
{

    protected function rules()
    {
        return [
            [
                'allow' => true,
                'roles' => $this->getAllRoles(true),
            ]
        ];
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST']
        ];
    }

    public function actionIndex()
    {
        $form = new PageForm();
        if (!$form->load(Yii::$app->getRequest()->post())) {
            return $this->response(false, 'can not load parameter');
        }
        if (($data = $form->initPage()) === false) {
            return $this->response(false, $form->getFirstErrors());
        }
        return $this->response(true, 'get data complete', $data);
    }
}