<?php


namespace frontend\controllers;

use common\components\EcomobiComponent;
use Yii;
use yii\di\Instance;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
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

    public function ogMetaTag()
    {
        return [
            'title' => 'Weshop Global',
            'site_name' => Yii::$app->requestedRoute,
            'url' => $this->request->url,
            'image' => Url::to('/img/weshop-logo-vn.png'),
            'description' => '',
        ];
    }

    public function metaTag()
    {
        return [];

    }

    public function linkTag()
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
        $this->registerAllMetaTagLinkTag();
        $this->getEcomobi()->register();
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

    protected function registerAllMetaTagLinkTag()
    {
        $metaTags = ArrayHelper::merge([
            'author' => 'Weshop Global',
            'COPYRIGHT' => '&copy; Weshop Global',
            'robots' => 'noodp,index,follow',
            'cystack-verification' => 'f63c2e531bc93b353c0dbd93f8ce0505'
        ], $this->metaTag(), ArrayHelper::getValue(Yii::$app->params, 'metaTagParam', []));
        foreach ($metaTags as $name => $content) {
            $this->registerMetaTag([
                'name' => $name,
                'content' => $content !== null ? $content : '',
            ]);
        }
        $ogMetaTags = ArrayHelper::merge([
            'type' => 'website',
            'locale' => 'vi_VN',
            'image:height' => 200,
            'image:width' => 150,

        ], $this->ogMetaTag(), ArrayHelper::getValue(Yii::$app->params, 'ogMetaTagParam', []));
        foreach ($ogMetaTags as $name => $content) {
            $this->registerMetaTag([
                'property' => "og:$name",
                'name' => $name,
                'content' => $content !== null ? $content : '',
            ]);
        }
        $links = ArrayHelper::merge([
            'shortcut icon' => Url::to('/img/icons/favicon.ico', true),
        ], $this->linkTag(), ArrayHelper::getValue(Yii::$app->params, 'linkTagParam', []));
        foreach ($links as $rel => $href) {
            $this->registerLinkTag([
                'rel' => $rel,
                'href' => $href !== null ? $href : '#'
            ]);
        }

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
            return Yii::$app->ga->request()
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
            return ['success' => false, 'message' => 'Form Security Alert', 'data' => ['content' => '']];
        }
        Yii::info("_uuid : " . $this->_uuid);
        if (!Yii::$app->user->isGuest) {

            // User Create / register Weshop
            $id = Yii::$app->user->identity->getId();
            $email = Yii::$app->user->identity->email;
            $this->_uuid = $id.'WS'.$email;
            /** Update UUID == fingerprint **/
            $User = new User();
            if( ($User = $User->findByUuid($id)) != null && !$User->uuid){
                Yii::info("Insert uuid : Ok ! ".$this->_uuid. " pages ". $this->setDocumentPath);
                $User->uuid = $this->_uuid;
                $User->id = $id;
                $User->last_update_uuid_time = Yii::$app->formatter->asDateTime('now');
                $User->last_update_uuid_time_by = 99999;
                if($User->update())
                {
                    Yii::info("Insert uuid : Ok ! ");
                }else {
                    Yii::info("Insert uuid : Error ! ");
                    Yii::info([
                        'Error' => $User->errors,
                    ], __CLASS__);
                }
            }
            Yii::info("Update : ".$this->_uuid);
        }else {
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

        if($this->_uuid){
            if((YII_ENV == 'dev' and YII_DEBUG == true) || (Yii::$app->params['ENV'] == true) ){
                // ENV DEV /  TEST
                $this->gaWs();
            }else if((YII_ENV == 'prod' and YII_DEBUG == false) || (Yii::$app->params['ENV'] == false) ) {

                // ENV PROD
            }
        } else {
            return ['success' => false, 'message' => 'fingerprint null', 'data' => ['content' => '']];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return 'Ok ! ' . $this->_uuid . ' pages ' . $this->setDocumentPath;
    }


}
