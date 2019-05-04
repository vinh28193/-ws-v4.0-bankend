<?php


namespace frontend\controllers;


class FrontendController extends \yii\web\Controller
{

    public function actionNotFound()
    {
        return $this->render('404', func_get_args());
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}