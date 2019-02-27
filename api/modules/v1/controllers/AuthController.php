<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 23/02/2019
 * Time: 09:14
 */

namespace api\modules\v1\controllers;


use api\behaviours\Apiauth;
use api\behaviours\Verbcheck;
use yii\filters\AccessControl;
use Yii;
use common\models\LoginForm;
use common\models\AuthorizationCodes;
use common\models\AccessTokens;

use backend\models\SignupForm;


class AuthController extends RestController
{
    public function behaviors()
    {

        $behaviors = parent::behaviors();

        return $behaviors + [
                'apiauth' => [
                    'class' => Apiauth::className(),
                    'exclude' => [],
                    'callback'=>[]
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => [
                                'index'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [],
                            'allow' => true,
                            'roles' => ['*'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => Verbcheck::className(),
                    'actions' => [
                        'index' => ['GET', 'POST'],
                        'create' => ['POST'],
                        'update' => ['PUT'],
                        'view' => ['GET'],
                        'delete' => ['DELETE']
                    ],
                ],

            ];
    }
}