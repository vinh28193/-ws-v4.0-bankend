<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 17:17
 */

namespace api\controllers;

use common\filters\AccessControl;
use common\filters\VerbFilter;
use common\filters\WeshopAuth;
use common\helpers\WeshopHelper;
use common\rbac\rules\RuleOwnerAccessInterface;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\web\Request;
use yii\web\Response;

class BaseApiController extends \yii\rest\Controller
{

    public $serializer = 'common\filters\Serializer';

    public $post;
    public $get;

    public $enableCsrfValidation = false;

    public $headers;

    public $type_user = 'user';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // remove authentication filter
        if (isset($behaviors['authenticator'])) {
            unset($behaviors['authenticator']);
        }
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => $this->getHttpOrigin(),
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ]

        ];
        // re-add authentication filter
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['options'], // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
            'authMethods' => [
                'httpBearer' => [
                    'class' => HttpBearerAuth::className()
                ],
                'weshopApi' => [
                    'class' => WeshopAuth::className()
                ],
                'httpHeader' => [
                    'class' => HttpHeaderAuth::className(),
                    'header' => 'X-Access-Token'
                ],
                'queryParam' => [
                    'class' => QueryParamAuth::className(),
                ]

            ],
        ];
        $behaviors['accessControl'] = [
            'class' => AccessControl::className(),
            'except' => ['options'],
            'defaultRules' => [
                [
                    'allow' => true,
                    'roles' => ['admin', 'superAdmin']
                ]
            ],
            'rules' => $this->rules(),
        ];
        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => $this->verbs(),
        ];
        return $behaviors;
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [
            '*' => ['OPTIONS']
        ];
    }

    /**
     * Declares the allowed access rule.
     * @return array
     */
    protected function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return array_merge(parent::actions(), [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ]);
    }

    public function init()
    {
        parent::init();
        $this->post = Yii::$app->request->post();
        $this->get = Yii::$app->request->get();
        $type = isset($this->get['name']) && $this->get['name'] ? $this->get['name'] : 'user';
        switch ($type) {
            case 'user':
            case 'admin':
                Yii::$app->user->identityClass = 'common\models\User';
                $this->type_user = 'user';
                break;
            /*
            case 'customer':
                Yii::$app->user->identityClass = 'common\models\Customer';
                $this->type_user = 'customer';
                break;
            */
            default:
                $this->type_user = 'user';
                Yii::$app->user->identityClass = 'common\models\User';
                break;
        }

        Yii::configure(Yii::$app, [
            'components' => [
                'request' => [
                    'class' => Request::className(),
                    'enableCookieValidation' => false,
                    'enableCsrfCookie' => false,
                    'parsers' => [
                        'application/json' => 'yii\web\JsonParser',
                    ]
                ],
                'response' => [
                    'class' => Response::className(),
                    'format' => Response::FORMAT_JSON,
//                    'formatters' => [
//                        Response::FORMAT_JSON => [
//                            'class' => 'yii\web\JsonResponseFormatter',
////                            'prettyPrint' => true, // use "pretty" output in debug mode
//                        ],
//                    ],
                ]
            ]
        ]);
    }

    protected function getHttpOrigin()
    {
        return array_merge(
            require(dirname(__DIR__) . '/config/http-origin.php'),
            is_file(dirname(__DIR__) . '/config/http-origin-local.php') ?
                require(dirname(__DIR__) . '/config/http-origin-local.php') : []
        );
    }

    /**
     * @param \yii\base\Action $action the action just executed.
     * @param mixed $result the action return result.
     * @return mixed the processed action result.
     */
    public function afterAction($action, $result)
    {
        $controllerId = $action->controller->id;
        /** @TODO : Luu API CALL RESPHONE **/
        $result = parent::afterAction($action, $result);
        $log = Yii::$app->wsLog;
//        if($driver = $log->getDriver($controllerId) !== null){
//            $request = Yii::$app->request;
////            $driver->push($action->id,isset($result['message']) ? $result['message'] : null,[
////                'response' => $result
////            ]);
//        }
        return $result;
    }

    /**
     * @param bool $success
     * @param string $message
     * @param array $data
     * @return array
     */
    public function response($success = true, $message = 'Ok', $data = [], $total = 0)
    {
        return WeshopHelper::response($success, $message, $data, $total);
    }

    /**
     * @param bool $keyOnly
     * @param array $exception
     * @return array
     */
    public function getAllRoles($keyOnly = false, $exception = [])
    {
        if (is_string($exception)) {
            $exception = [$exception];
        }
        $roles = [];
        foreach ((array)Yii::$app->getAuthManager()->getRoles() as $name => $role) {
            if (in_array($name, $exception)) {
                continue;
            }
            if ($keyOnly) {
                $roles[] = $name;
            } else {
                $roles[$name] = $role;
            }
        }
        return $roles;

    }

    /**
     * @param bool $throw
     * @return array
     * @throws \yii\web\ForbiddenHttpException
     */
    public function forbidden($throw = true)
    {
        $message = Yii::t('yii', 'You are not allowed to perform this action.');
        if ($throw) {
            throw new \yii\web\ForbiddenHttpException($message);
        }
        return $this->response(false, $message);
    }

    /**
     * @param $permissionName
     * @param array|string|\common\rbac\rules\RuleOwnerAccessInterface $params
     * @return bool
     * @throws \yii\web\ForbiddenHttpException
     */
    public function can($permissionName, $params)
    {
        if ($params instanceof RuleOwnerAccessInterface) {
            $params = $params->getRuleParams($permissionName);
        }
        if (Yii::$app->getUser()->can($permissionName, $params, true)) {
            return true;
        }
        $this->forbidden(true);
    }
}
