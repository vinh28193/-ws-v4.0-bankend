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
     * @return string
     */
    public function Uuid()
    {
        return $this->Uuid;
    }
}
