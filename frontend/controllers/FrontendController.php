<?php


namespace frontend\controllers;

use Yii;
use yii\di\Instance;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use yii\base\InvalidArgumentException;
use common\components\StoreManager;
use common\request\UUID;


/**
 * Class FrontendController
 * @package frontend\controllers
 *
 * @property-read  StoreManager $storeManager;
 * @property-read  Request $request;
 */
class FrontendController extends Controller
{
    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    /**
     * @var string|Request
     */
    public $request = 'request';

    /**
     * @var string | UUID
     */
    public $Uuid = '';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->request = Instance::ensure($this->request, Request::className());
    }

    /**
     * default params will be pass to view
     * @return array
     * @see FrontendController::render
     */
    public function defaultViewParams()
    {
        return [
            'storeManager' => $this->storeManager
        ];
    }


    /**
     * default params will be pass to current layout
     * @return array
     * @see FrontendController::render
     * @see FrontendController::$layout
     */
    public function defaultLayoutParams()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile($this->getView());
        if ($layoutFile !== false) {
            $params = $this->defaultLayoutParams();
            if (isset($params['content'])) {
                throw new InvalidArgumentException("parameter name: `content` can not pass to layout: `$layoutFile`, please try another name");
            }
            $params['content'] = $content;
            return $this->getView()->renderFile($layoutFile, $params, $this);
        }
        return $content;
    }

    /**
     * @inheritDoc
     */
    public function render($view, $params = [])
    {
        $params = array_merge($this->defaultViewParams(), $params);
        return parent::render($view, $params);
    }

    /**
     * @param $options
     * @param null $key
     */
    public function registerMetaTag($options, $key = null)
    {
        $this->getView()->registerMetaTag($options, $key);
    }

    /**
     * @param $options
     * @param null $key
     */
    public function registerLinkTag($options, $key = null){
        $this->getView()->registerLinkTag($options, $key);
    }

    /**
     * @var string
     * Set Path GA
     */
    public $setDocumentPath;
    public function gaWs()
    {
        Yii::info("set Path : ".$this->setDocumentPath);
        Yii::info("this->_uuid : ".$this->_uuid);
        if($this->setDocumentPath){
            Yii::info("FrondEnd Pages GA WS");
           return Yii::$app->ga->request()
                ->setClientId($this->_uuid)
                ->setDocumentPath($this->setDocumentPath)
                ->setAsyncRequest(true)
                ->sendPageview();
        }
    }

    public $_uuid ;
    public function actionU()
    {
        $post = $this->request->post();
        if (isset($post['fingerprint']) and isset($post['path'])) {
            $this->_uuid = $post['fingerprint'];
            $this->setDocumentPath = $post['path'];
            Yii::info("fingerprint : ".$this->_uuid);
            Yii::info("fingerprint : ".$this->setDocumentPath);
        }
        if (!Yii::$app->getRequest()->validateCsrfToken() || $this->_uuid === null ) {
            return ['success' => false,'message' => 'Form Security Alert', 'data' => ['content' => ''] ];
        }
        Yii::info("_uuid : ".$this->_uuid);

        if (!Yii::$app->user->isGuest) {
            $this->_uuid = Yii::$app->user->identity->getId().'WS'.Yii::$app->user->identity->email;
        }else {
            $this->_uuid = isset($this->_uuid) ? $this->_uuid : 99999;
        }

        if($this->_uuid){
            if(YII_ENV == 'dev' and YII_DEBUG == true){
                // ENV DEV /  TEST
                $this->gaWs();
            }else {
                // ENV PROD
            }
        }else {
            return ['success' => false,'message' => 'fingerprint null', 'data' => ['content' => ''] ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok ! '.$this->_uuid. ' pages '. $this->setDocumentPath;
    }


}
