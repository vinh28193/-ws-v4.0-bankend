<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 23/02/2019
 * Time: 11:06
 */

namespace backend\modules\v1\weshop\controllers;


use backend\behaviours\Apiauth;
use backend\behaviours\Verbcheck;
use yii\filters\AccessControl;

class BaseAuthorController extends BaseController
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