<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-15
 * Time: 16:58
 */

namespace common\components;


use common\models\Store;
use frontend\assets\WeshopAsset;
use landing\LandingAsset;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\helpers\WeshopHelper;

/**
 * Class Store
 * @package common\components
 *
 * @property-read  Store $store
 * @property-read  string $domain
 * @property-read  integer $id
 */
class StoreManager extends Component implements BootstrapInterface
{

    const STORE_VN = 1;
    const STORE_ID = 7;

    const MONEY_SHOW_MODE_CURRENCY = 'currency';
    const MONEY_SHOW_MODE_SYMBOL = 'symbol';

    /**
     * @var integer
     */
    public $storeId = 1;
    /**
     * @var array
     */
    public $defaultDomain;

    public $moneyShowWith = self::MONEY_SHOW_MODE_CURRENCY;
    /**
     * @var Store;
     */
    private $_store;

    /**
     * initialize store
     */

    public function init()
    {
        parent::init();
        if ($this->storeId === null) {
            throw new InvalidConfigException(get_class($this) . ":storeId can not be null");
        }
    }

    /**
     * @param yii\web\Application $app
     */
    public function bootstrap($app)
    {
        $url_arr = explode('/',$app->request->url);
        $app->getView();
        if($url_arr && count($url_arr) > 1){
            if($url_arr[0] && strpos('-'.$url_arr[0], 'landing')){}
            elseif ($url_arr[1] && strpos('-'.$url_arr[1], 'landing')){}
            else{
                WeshopAsset::register($app->view);
            }
        }else{
            WeshopAsset::register($app->view);
        }
        $options = Json::htmlEncode($this->getClientOptions());
        $app->view->registerJs("ws.init($options);");
        $messages = Json::htmlEncode($this->getClientMessages());
        $app->view->registerJs("ws.i18nLoadMessages($messages);");
        // $app->view->registerJs("console.log(ws.t('Hello {name}',{name:'Vinh'}))");
    }

    protected function getClientOptions()
    {
        return [
            'currency' => $this->store->{$this->moneyShowWith},
            'priceDecimal' => 0,
            'pricePrecision' => -3,
        ];
    }

    protected function getClientMessages()
    {
        $results = [
            'Error' => Yii::t('javascript','Error'),
            'Success' => Yii::t('javascript','Success'),
            'Confirm' => Yii::t('javascript','Confirm'),
            'Delete' => Yii::t('javascript','Delete'),
            'Select' => Yii::t('javascript','Select'),
            'Not Found' => Yii::t('javascript','Not Found'),
            'Cannot change quantity' => Yii::t('javascript','Cannot change quantity'),
            'You can not buy greater than {number}' => Yii::t('javascript','You can not buy greater than {number}'),
            'You can not buy lesser than {number}' => Yii::t('javascript','You can not buy lesser than {number}'),
            'Out of stock' => Yii::t('javascript','Out of stock'),
            'Please select the variation' => Yii::t('javascript','Please select the variation'),
            'Please fill full the buyer information' => Yii::t('javascript','Please fill full the buyer information'),
            'Please fill full the receiver information' => Yii::t('javascript','Please fill full the receiver information'),
            'You must agree to the {name} terms' => Yii::t('javascript','You must agree to the {name} terms'),
            'You need to top up over {amount}' => Yii::t('javascript','You need to top up over {amount}'),
            'You have not agreed to {name}\'s terms and conditions of trading' => Yii::t('javascript','You have not agreed to {name}\'s terms and conditions of trading'),
            '{days} days' => Yii::t('javascript','{days} days')
        ];
        return $results;
    }

    /**
     * getter
     * @return Store
     * @throws NotFoundHttpException
     */
    public function getStore()
    {
        if ($this->_store === null) {
            /** @var $class Store */
            $this->_store = Store::getActiveStore(['id' => $this->getId()]);
            if ($this->_store === null) {
                throw new NotFoundHttpException("not found store {$this->getId()}");
            }
        }
        return $this->_store;
    }

    /**
     * @param $storeId
     * @throws NotFoundHttpException
     */
    public function setStore($storeId)
    {
        $this->_store = Store::getActiveStore(['id' => $storeId]);
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        if ($this->store !== null) {
            $host = $this->store->url;
        } else if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else if (isset($_SERVER['HOSTNAME'])) {
            $host = $_SERVER['HOSTNAME'];
        } else if (isset($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } else {
            $host = $this->defaultDomain;
        }
        return $host;
    }

    public function getDomainByStoreId($idStore = null)
    {
        $idStore = $idStore ? $idStore : $this->store->id;

        if ($idStore == self::STORE_VN and !YII_ENV_TEST) {
            $host = "weshop.com.vn";
        } else if ($idStore == self::STORE_VN and YII_ENV_TEST) {
            $host = "web-uat-v3.weshop.com.vn";
        } else if ($idStore == self::STORE_ID and !YII_ENV_TEST) {
            $host = "weshop.co.id";
        } else if ($idStore == self::STORE_ID and YII_ENV_TEST) {
            $host = "uat-in.weshop.asia";
        } else if ($idStore == self::STORE_ID and YII_ENV_TEST) {
            $host = "uat-indo-v4.weshop.asia";
        } else if ($idStore == self::STORE_ID and YII_ENV_TEST) {
            $host = "weshop-v4-id.front-end.local.id";
        } else {
            $host = "weshop.com.vn";
        }
        return $host;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->storeId;
    }

    public function __call($name, $params)
    {
//        $country = 'VN';
//        $getter = "is$country";
//        $reg = "^/is[A-Z]{2}/$";
//        if ($name === $getter) {
//
//        }
        return parent::__call($name, $params); // TODO: Change the autogenerated stub
    }

    private $_exRate;

    public function getExchangeRate()
    {
        if (!$this->_exRate) {
            /** @var  $exRate ExchangeRate */
            $exRate = Yii::$app->exRate;
            $this->_exRate = $exRate->load('USD', $this->store->currency);
        }
        return $this->_exRate;
    }

    public function setExchangeRate($rate)
    {
        $this->_exRate = $rate;
    }

    public function getLanguageId()
    {
        return $this->store->locale;
    }

    public function roundMoney($money)
    {
        return WeshopHelper::roundNumber($money, -3);
    }

    public function showMoney($money, $currency = null , $isRound = true)
    {
        $money = $isRound ? $this->roundMoney($money) : $money;
        if ($currency === null) {
            $currency = $this->getCurrencyName();
        }
        $decimal = 0;
        if ($currency === 'USD' || $currency === '$') {
            $decimal = 2;
        }
        $money = number_format($money, $decimal);
        $money = StringHelper::normalizeNumber($money);
        return implode($this->moneyShowWith === self::MONEY_SHOW_MODE_CURRENCY ? ' ' : '', [$money, $currency]);
    }


    /**
     * Todo 1 trường trong db table store và rewrite hàm magic __get để magic property $isVN,$isID
     * @return bool
     */
    public function isVN()
    {
        return $this->getId() == self::STORE_VN;
    }

    public function isID()
    {
        return $this->getId() == self::STORE_ID;
    }

    public function getBaseUrl()
    {
        $urlBase = $this->getDomainByStoreId();
        $urlBase = str_replace('http://', 'https://', strtolower($urlBase));
        if (strpos($urlBase, 'https://') == false) {
            $urlBase = 'https://' . $urlBase;
        }
        return $urlBase;
    }

    public function getName()
    {
        if ($this->isID())
            return "Weshop Indonesia";
        if ($this->isVN())
            return "Weshop Việt Nam";
        return "Weshop Global";
    }

    public function getCurrencyName($type = null)
    {
        if ($type === null) {
            $type = $this->moneyShowWith;
        }
        return $this->store->{$type};
    }

}
