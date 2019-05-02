<?php
namespace api\controllers;
use Yii;
use api\controllers\BaseApiController;
use common\wsTelegramChatPush\TelegramTarget;


class WsTelegramChatPushController extends BaseApiController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        $except = array_merge($auth['except'],[
            'index',
        ]);
        $auth['except'] = $except;
        $behaviors['authenticator'] = $auth;
        return $behaviors;
    }

    protected function rules()
    {
        return [
            [
                'actions' => ['index'],
                'allow' => true
            ],
            [
                'actions' => ['signup'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'actions' => ['logout', 'me'],
                'allow' => true,
                'roles' => ['@'],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['POST','GET'],
        ];
    }

    public function actionIndex()
    {
        $wsPush = new TelegramTarget();
        $res = $wsPush->export();
        return $this->response(true, 'success', $res);
    }

}
