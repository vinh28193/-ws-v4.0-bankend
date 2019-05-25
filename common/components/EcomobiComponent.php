<?php


namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\web\Cookie;
use yii\web\Request;

/**
 * Class EcomobiComponent
 * @package common\components
 *
 *
 * @property Cookie $cookie
 * @property-read string $cookieKey
 * @property-read array $data
 * @property-read Request $request
 */
class EcomobiComponent extends Component
{

    public $enable = true;
    public $baseApiUrl;
    public $token;
    public $offerId = 'weshop';
    public $httpClientConfig = [];
    public $cookieName = '__ECOMOBI';

    public $queryParam = ['utm_source', 'traffic_id', 'utm_medium'];
    public $utmSourceParam = 'utm_source';
    public $utmSourceIdentity = 'ecomobi';
    public $aliasParam = 'traffic_id';


    /**
     * @var Request
     */
    private $_request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Yii::$app->request;
        }
        return $this->_request;
    }

    public function register()
    {
        if (!$this->enable) {
            return;
        }
        $params = [];
        foreach ($this->queryParam as $name) {
            $params[$name] = $this->request->getQueryParam($name, '');
        }

        if (
            isset($params[$this->utmSourceParam]) &&
            $params[$this->utmSourceParam] === $this->utmSourceIdentity &&
            $params[$this->utmSourceParam] !== ''
        ) {
            $cookie = $this->getCookie();
            $value = ($cookie instanceof Cookie && $cookie->value !== null) ? Json::decode($cookie->value, true) : [];

            if (
                count($value) === 0 ||
                (
                    isset($value[$this->aliasParam]) &&
                    $value[$this->aliasParam] !== $params[$this->aliasParam] &&
                    $params[$this->aliasParam] !== ''
                )
            ) {
                $data = Json::encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $this->setCookie($data);
            }
        }
    }

    /**
     * Read tại mỗi Request (nếu mà Response trước bị xóa thì Request ra null)
     * [[CookieCollection::has($name)]] check time expire together
     * @return null|Cookie
     */
    public function getCookie()
    {
        $cookies = Yii::$app->getRequest()->getCookies();
        if ($cookies->has($this->cookieName)) {
            return $cookies->get($this->cookieName);
        }
        return null;
    }

    /**
     * Add vào Response
     * @param $value
     */
    public function setCookie($value)
    {
        $cookies = Yii::$app->getResponse()->getCookies();

        $cookie = new Cookie([
            'name' => $this->cookieName,
            'value' => $value,
            'expire' => time() + 86400,
            'secure' => true
        ]);
//        if($cookies->has($this->getCookieKey())){
//            $cookies->remove($cookie);
//        }

        $cookies->add($cookie);

    }

    /**
     * Remove tại Response, như vậy sau khi remove thì lần Request tiếp theo sẽ không còn cookie nữa
     */
    public function removeCookie()
    {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookie = new Cookie([
            'name' => $this->cookieName,
            'secure' => true
        ]);
        $cookies->remove($cookie);
    }

    private $_data;

    public function getDatas()
    {
        return $this->_data;
    }

    public function setDatas($data)
    {
        $this->_data = $data;
    }

    public function getData($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    public function addData($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function prepareData()
    {
        if ($this->getData('token') === null) {
            $this->addData('token', $this->token);
        }
        if ($this->getData('offer_id') === null) {
            $this->addData('offer_id', $this->offerId);
        }
        $this->addData('session_ip', $this->request->getUserIP());
    }

    /**
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function send()
    {
        if ($this->enable) {
            return false;
        }
        $cookie = $this->getCookie();
        $value = ($cookie instanceof Cookie && $cookie->value !== null) ? json_decode($cookie->value, true) : [];
        if (is_array($value) && count($value) === 3 && $value[$this->aliasParam] !== '' && $value[$this->utmSourceParam] !== '') {
            $this->prepareData();
            $this->addData($this->aliasParam, $value[$this->aliasParam]);
            $http = $this->httpClientConfig;
            $http['transport'] = CurlTransport::className();
            $http = Yii::createObject($http);
            /** @var $http \yii\httpclient\Client */
            $request = $http->createRequest();
            $request->setFormat(Client::FORMAT_JSON);
            $request->setMethod('POST');
            $request->setUrl('api/postback');
            $request->setData($this->getDatas());
            $cloner = clone $request;
            $response = $request->send();
            if (!$response->isOk) {
                Yii::error($response->getData(), '\yii\httpclient\Client::send');
                return false;
            }
            $response = $response->getData();
            $this->saveLog($cloner->toString(), $response);
            if ($response['success'] === true) {
                $this->removeCookie();
                return [$value, $response['data']];
            }
            return false;

        }
        return false;
    }

    public function saveLog($request, $response)
    {

    }
}