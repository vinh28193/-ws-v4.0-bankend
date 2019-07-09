<?php


namespace frontend\controllers;

use common\components\EcomobiComponent;
use common\components\UserCookies;
use common\models\db\SystemExchangeRate;
use Yii;
use yii\di\Instance;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\InvalidArgumentException;
use common\components\StoreManager;
use common\request\UUID;
use common\models\User;


/**
 * Class FrontendController
 * @package frontend\controllers
 *
 * @property-read  StoreManager $storeManager;
 * @property-read  Request $request;
 */
class FrontendController extends Controller
{

    const UUID_COOKIE_NAME = '__UuidCookieName';

    const UUID_HEADER_TOKEN = 'X-Fingerprint-Token';
    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    /**
     * @var
     */
    public $site_title;

    public $site_name;

    public $site_description;

    public $site_image;
    /**
     * @var string|Request
     */
    public $request = 'request';


    /**
     * @var EcomobiComponent
     */
    private $_ecomobi;

    /**
     * @return EcomobiComponent|mixed
     */
    public function getEcomobi()
    {
        if (!is_object($this->_ecomobi)) {
            $this->_ecomobi = Yii::$app->ecomobi;
        }
        return $this->_ecomobi;
    }

    /**
     * @var \baibaratsky\yii\google\analytics\MeasurementProtocol
     */
    private $_ga;

    /**
     * @return baibaratsky\yii\google\analytics\MeasurementProtocol
     */
    public function getGa()
    {
        if (!is_object($this->_ga)) {
            $this->_ga = Yii::$app->ga;
        }
        return $this->_ga;
    }

    protected function ogMetaTag()
    {
        return [
            'title' => $this->site_title,
            'site_name' => $this->site_name,
            'url' => $this->request->absoluteUrl,
            'image' => $this->site_image,
            'description' => $this->site_description,
        ];
    }

    protected function metaTag()
    {
        return [
            'title' => $this->site_title,
            'site_name' => $this->site_name,
            'url' => $this->request->absoluteUrl,
            'image' => $this->site_image,
            'description' => $this->site_description,
        ];

    }

    protected function linkTag()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->request = Instance::ensure($this->request, Request::className());
        $this->site_title = $this->storeManager->store->name;
        $this->site_name = $this->storeManager->store->name;
        $this->site_description = Yii::t('frontend', '{name} - World Wide Shopping Made Easy', [
            'name' => $this->site_name
        ]);
        $this->site_image =  Url::to('/img/weshop-logo-vn.png', true);
        $this->getEcomobi()->register();
    }

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        Yii::$app->response->setStatusCode(200);
        if ($this->filterUuid(false) === null && ($onHead = $this->request->getHeaders()->get(self::UUID_HEADER_TOKEN, null)) !== null) {
            $this->setUuidCookie($onHead);
        }
        if (parent::beforeAction($action)) {
            if ($this->request->isAjax) {

            }
            return true;
        }

        return false;
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
                throw new InvalidArgumentException(Yii::t('frontend', 'parameter name: `content` can not pass to layout: `{layoutFile}`, please try another name', [
                    'layoutFile' => $layoutFile
                ]));
            }
            $params['content'] = $content;
            $view = $this->getView();
            $view->title = $this->site_title;
            $metaTags = ArrayHelper::merge([
                'author' => $this->storeManager->store->name,
                'COPYRIGHT' => $this->storeManager->store->name,
                'robots' => 'index,follow',
                'fingerprint-token' => ($uuid = $this->filterUuid(false)) !== null ? $uuid : '',
            ], $this->metaTag(), ArrayHelper::getValue(Yii::$app->params, 'metaTagParam', []));
            foreach ($metaTags as $name => $content) {
                $view->registerMetaTag([
                    'name' => $name,
                    'content' => $content !== null ? $content : '',
                ]);
            }
            $ogMetaTags = ArrayHelper::merge([
                'type' => 'website',
                'locale' => Yii::$app->language,

            ], $this->ogMetaTag(), ArrayHelper::getValue(Yii::$app->params, 'ogMetaTagParam', []));
            foreach ($ogMetaTags as $name => $content) {
                $view->registerMetaTag([
                    'property' => "og:$name",
                    'name' => $name,
                    'content' => $content !== null ? $content : '',
                ]);
            }
            $links = ArrayHelper::merge([
                'shortcut icon' => Url::to('/favicon.ico', 'https'),
            ], $this->linkTag(), ArrayHelper::getValue(Yii::$app->params, 'linkTagParam', []));
            foreach ($links as $rel => $href) {
                $view->registerLinkTag([
                    'rel' => $rel,
                    'href' => $href !== null ? $href : '#'
                ]);
            }
            return $view->renderFile($layoutFile, $params, $this);
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
    public function registerLinkTag($options, $key = null)
    {
        $this->getView()->registerLinkTag($options, $key);
    }

    /**
     * @var string
     * Set Path GA
     */
    public $setDocumentPath;

    public function gaWs()
    {
        Yii::info("set Path : " . $this->setDocumentPath);
        Yii::info("this->_uuid : " . $this->_uuid);
        if ($this->setDocumentPath) {
            Yii::info("FrondEnd Pages GA WS");
            return $this->getGa()->request()
                ->setClientId($this->_uuid)
                ->setDocumentPath($this->setDocumentPath)
                ->setAsyncRequest(true)
                ->sendPageview();
        }
    }

    public $_uuid;

    public function actionU()
    {
        $post = $this->request->post();
        if (isset($post['fingerprint']) and isset($post['path'])) {
            $this->_uuid = $post['fingerprint'];
            $this->setDocumentPath = $post['path'];
            Yii::info("fingerprint : " . $this->_uuid);
            Yii::info("fingerprint : " . $this->setDocumentPath);
        }
        if (!Yii::$app->getRequest()->validateCsrfToken() || $this->_uuid === null) {
            return ['success' => false, 'message' => Yii::t('frontend', 'Form Security Alert'), 'data' => ['content' => '']];
        }
        Yii::info("_uuid : " . $this->_uuid);
        if (!Yii::$app->user->isGuest) {
            /** @var  $identity  User */
            $identity = Yii::$app->user->identity;
            // User Create / register Weshop
            /** Update UUID == fingerprint **/
            if (!$identity->uuid || ($identity->uuid !== null && $identity->uuid !== $this->_uuid)) {
                Yii::info("Insert uuid : Ok ! " . $this->_uuid . " pages " . $this->setDocumentPath);
                $identity->updateAttributes([
                    'uuid' => $this->_uuid,
                    'last_update_uuid_time' => Yii::$app->formatter->asDateTime('now'),
                    'last_update_uuid_time_by' => 99999
                ]);
            }
            Yii::info("Insert uuid User Weshop : " . $this->_uuid);
            Yii::info([
                '_uuid' => $this->_uuid,
                'identity' => $identity
            ], __CLASS__);
            $this->_uuid = $identity->getFingerprint();
        } else {
            // anynomus
            /*
            $this->_uuid = isset($this->_uuid) ? $this->_uuid : 99999;
            // Update fingerprint = $this->_uuid
            $User = new User();
            if( $User->findClientidga($this->_uuid) != null) {
                $User->client_id_ga = $this->_uuid;
                $User->last_update_client_id_ga_time_by = 99999;
                $User->save(false);
            }
            */
        }
        $this->setUuidCookie($this->_uuid);
        if ($this->_uuid) {
            if ((YII_ENV == 'dev' and YII_DEBUG == true) || (Yii::$app->params['ENV'] == true)) {
                // ENV DEV /  TEST
                $this->gaWs();
            } else if ((YII_ENV == 'prod' and YII_DEBUG == false) || (Yii::$app->params['ENV'] == false)) {

                // ENV PROD
            }
        } else {
            return ['success' => false, 'message' => Yii::t('frontend', 'fingerprint is null'), 'data' => ['content' => '']];
        }
        $cookUser = new UserCookies();
        $cookUser->setUser();
        $cookUser->uuid = $this->_uuid;
        $cookUser->setNewCookies();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok ! ' . $this->_uuid . ' pages ' . $this->setDocumentPath;
    }

    public function getUuidCookie()
    {
        $cookies = Yii::$app->getRequest()->getCookies();
        if ($cookies->has(self::UUID_COOKIE_NAME)) {
            return $cookies->getValue(self::UUID_COOKIE_NAME);
        }
        return null;
    }

    public function setUuidCookie($uuid)
    {
        $cookies = Yii::$app->getResponse()->getCookies();

        $cookie = new Cookie([
            'name' => self::UUID_COOKIE_NAME,
            'value' => $uuid,
            'expire' => time() + 86400,
            'secure' => false
        ]);

        $cookies->add($cookie);
    }

    public function removeUuidCookie()
    {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookie = new Cookie([
            'name' => self::UUID_COOKIE_NAME,
            'secure' => false
        ]);
        $cookies->remove($cookie);

    }

    public function filterUuid($checkHead = true)
    {
        if ($this->_uuid !== null) {
            return $this->_uuid;
        } elseif (($identity = Yii::$app->user->identity) !== null) {
            /** @var  $identity User */
            $uuid = $identity->getFingerprint();
            $this->setUuidCookie($uuid);
            return $uuid;
        } elseif (($onCookie = $this->getUuidCookie()) !== null) {
            return $onCookie;
        } elseif ($checkHead && ($onHead = $this->request->getHeaders()->get(self::UUID_HEADER_TOKEN, null) !== null)) {
            return $onHead;
        }
        return null;
    }
}
