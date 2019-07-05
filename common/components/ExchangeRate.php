<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-01
 * Time: 09:26
 */

namespace common\components;

use common\models\db\SystemCurrency;
use common\models\User;
use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\caching\CacheInterface;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\Inflector;
use yii\httpclient\Client;

class ExchangeRate extends Component
{

    use GetUserIdentityTrait;

    public $defaultRatePercent = 0; // 0%

    public $ratePercents = [];

    /**
     * @var string | Connection
     */
    public $db = 'db';
    /**
     * @var string|CacheInterface
     */
    public $cache = 'cache';
    public $cacheDuration = 10800;
    public $currencies = [
        'USD', 'VND', 'MYR', 'THB', 'SGD', 'IDR', 'PHP', 'GBP', 'JPY',
    ];

    public $symbols = [
        'USD' => '$',
        'VND' => 'VND',
        'MYR' => 'MYR',
        'THB' => 'THB',
        'SGD' => 'SGD',
        'IDR' => 'IDR',
        'PHP' => 'PHP',
        'GBP' => 'GBP',
        'JPY' => 'JPY',
    ];
    /**
     * @var string
     */
    public $exchangeRateTable = '{{%system_exchange_rate}}';
    /**
     * @var array
     * Mặc định cho cao hơn hẳn
     */
    public $defaultTo = [
        'USD,VND' => 23500,
        'JPY,VND' => 21000
    ];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
    }


    private $_rates;

    /**
     * @param $from
     * @param $to
     * @return mixed|null
     */
    public function load($from, $to)
    {
        if (!in_array($from, $this->currencies)) {
            throw  new InvalidArgumentException("invalid currency from $from not in config");
        }
        if (!in_array($to, $this->currencies)) {
            throw  new InvalidArgumentException("invalid currency to $to not in config");
        }
        $key = $this->buildKey($from, $to);
        $this->loadFromCache($from, $to);
        $rate = isset($this->_rates[$key]) ? $this->_rates[$key] : $this->setDefault($from, $to);
        $ratePercent = $this->getRatePercent();
        return ($rate * $ratePercent) + $rate;
    }

    /**
     * @param $from
     * @param $to
     * @param bool $refresh
     */

    protected function getRatePercent()
    {

        if (($user = $this->getUser()) === null) {
            Yii::info("Rate percent for guest : {$this->defaultRatePercent}", __METHOD__);
            return $this->defaultRatePercent;
        }

        $level = $user->getUserLevel();

        if (isset($this->ratePercents[$level]) && $this->ratePercents[$level] > 0) {
            Yii::info("Rate percent for user `{$user->id}` level `$level`: {$this->ratePercents[$level]}", __METHOD__);
            return $this->ratePercents[$level];
        }
        Yii::info("Default rate percent : {$this->defaultRatePercent}", __METHOD__);
        return $this->defaultRatePercent;
    }

    protected function loadFromCache($from, $to, $refresh = false)
    {
        $key = $this->buildKey($from, $to);
        if (!isset($this->_rates[$key]) || !($this->_rates[$key] = $this->cache->get($key)) || $refresh) {
            $this->_rates[$key] = $this->loadFromDb($from, $to);
        }
        $this->cache->set($key, $this->_rates[$key], $this->cacheDuration);
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    protected function loadFromDb($from, $to)
    {
        $this->invalidCache($from, $to);
        $query = new Query();
        $query->select(['rate' => 'r.rate']);
        $query->from(['r' => $this->exchangeRateTable]);
        $query->where([
            'AND',
            ['r.from' => strtoupper($from)],
            ['r.to' => strtoupper($to)]
        ]);
        $query->orderBy(['r.id' => SORT_DESC]);
        $query->limit(1);
        return $query->one($this->db)['rate'];
    }

    public function addData($from, $to, $rate)
    {
        $transaction = $this->db->beginTransaction();
        try {
            $this->db->createCommand()
                ->insert($this->exchangeRateTable, [
                    'from' => strtoupper($from),
                    'to' => strtoupper($to),
                    'rate' => $rate,
                    'sync_at' => Yii::$app->getFormatter()->asDatetime('now')
                ])->execute();
            $transaction->commit();
            return true;
        } catch (\Exception $exception) {
            Yii::error($exception, __METHOD__);
            $transaction->rollBack();
            return false;
        }
    }

    public function loadFromApi($console = false)
    {
        if ($console) echo "Lấy tỷ giá từ apilayer: ,,," . PHP_EOL;
        $from = 'USD';
        $transaction = $this->db->beginTransaction();
        try {
            $curl = curl_init('http://www.apilayer.net/api/live?access_key=3c96a96b7700b09c5803dbf858ab9af0&fromat=1');
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            $result = json_decode($result, true);
            foreach ($result['quotes'] as $key => $quote) {
                $to = str_replace('USD', '', $key);
                if (in_array($to, $this->currencies)) {
                    $rate = $quote;
                    $key = $this->buildKey($from, $to);
                    $this->_rates[$key] = $rate;
                    $this->db->createCommand()
                        ->insert($this->exchangeRateTable, [
                            'from' => strtoupper($from),
                            'to' => strtoupper($to),
                            'rate' => $rate,
                            'sync_at' => Yii::$app->getFormatter()->asDatetime('now')
                        ])->execute();
                    if ($console) echo $from . " ==> " . $to . ": " . $rate . PHP_EOL;
                }
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            if ($console) echo "Có lỗi sảy ra. Huỷ tất cả các tỷ giá vừa lấy  ..." . PHP_EOL;
            if ($console) echo $exception->getFile() . PHP_EOL;
            if ($console) echo $exception->getMessage() . PHP_EOL;
            if ($console) echo $exception->getLine() . PHP_EOL;
            if ($console) echo $exception->getTraceAsString() . PHP_EOL;
            Yii::error($exception, __METHOD__);
            $transaction->rollBack();
        }

    }


    public function loadFromVcb($console = false)
    {
        if ($console) echo "Lấy tỷ giá từ VietCombank: ,,," . PHP_EOL;
        $to = 'VND';
        $url = 'https://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx';

        $transaction = $this->db->beginTransaction();
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl($url)
                ->send();
            $rs = $response->getContent();
            $rs = XmlUtility::xmlToArray($rs);
            if (!empty($rs['Exrate'])) {
                foreach ($rs['Exrate'] as $key => $val) {
                    $from = $val['@attributes']['CurrencyCode'];
                    if (in_array($from, $this->currencies)) {
                        $rate = $val['@attributes']['Sell'];
                        $key = $this->buildKey($from, $to);
                        $this->_rates[$key] = $rate;
                        $this->db->createCommand()
                            ->insert($this->exchangeRateTable, [
                                'from' => strtoupper($from),
                                'to' => strtoupper($to),
                                'rate' => $rate,
                                'sync_at' => Yii::$app->getFormatter()->asDatetime('now')
                            ])->execute();
                        if ($console) echo $from . " ==> " . $to . ": " . $rate . PHP_EOL;
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            if ($console) echo "Có lỗi sảy ra. Huỷ tất cả các tỷ giá vừa lấy  ..." . PHP_EOL;
            if ($console) echo $exception->getFile() . PHP_EOL;
            if ($console) echo $exception->getMessage() . PHP_EOL;
            if ($console) echo $exception->getLine() . PHP_EOL;
            if ($console) echo $exception->getTraceAsString() . PHP_EOL;
            Yii::error($exception, __METHOD__);
            $transaction->rollBack();
        }
    }

    /**
     * @param $from
     * @param $to
     * @return mixed|null
     */
    public function setDefault($from, $to)
    {
        $key = implode(',', [$from, $to]);
        return isset($this->defaultTo[$key]) ? $this->defaultTo[$key] : null;
    }

    /**
     * @param $from
     * @param $to
     * @return string
     */
    protected function buildKey($from, $to)
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        return 'c-rate-' . $from . '-' . $to;
    }

    /**
     * @param $from
     * @param $to
     */
    public function invalidCache($from, $to)
    {
        $key = $this->buildKey($from, $to);
        $this->cache->set($key, 0);
    }

    public function __call($name, $params)
    {
        $func = Inflector::camelize($name);

        return parent::__call($name, $params);
    }

    /**
     * @param $currency
     * @return mixed
     */
    public function symbol($currency)
    {
        return isset($this->symbols[$currency]) ? $this->symbols[$currency] : $currency;
    }

    /**
     * @param $value
     * @param $from
     * @param $to
     * @param int $default
     * @param int $precision
     * @return float|int
     */
    public function convert($value, $from, $to, $default = 0, $precision = 0)
    {
        if (($rate = $this->load($from, $to)) === null) {
            Yii::warning("can not resolve current exchange rate from $from to $to, register with default $default");
            $rate = $default;
        }
        return \common\helpers\WeshopHelper::roundNumber($rate * $value, $precision);
    }

    /**
     * @param $value
     * @param $default
     * @return float|int
     */
    public function usdToVnd($value, $default = 1)
    {
        return $this->convert($value, 'USD', 'VND', $default, -3);
    }

    public function jpyToVnd($value, $default = 213)
    {
        return $this->convert($value, 'JPY', 'VND', $value * $default, -3);
    }

    /**
     * @param $value
     * @param $default
     * @return float|int
     */
    public function jpyToUsd($value, $default = 1)
    {
        $vnd = $this->jpyToVnd($value);
        $rateUs = $this->convert(1, 'USD', 'VND', $default, -3);
        $usd = $vnd / $rateUs;
        return \common\helpers\WeshopHelper::roundNumber($usd, 2);
    }

    /**
     * @param $value
     * @param $default
     * @return float|int
     */
    public function gpdToUsd($value, $default = 1)
    {
        return $this->convert($value, 'gpd', 'usd', $default, 2);
    }

    /**
     * @param $value
     * @param $default
     * @return float|int
     */
    public function gpdToVnd($value, $default = 1)
    {
        return $this->convert($value, 'gpd', 'vnd', $default, -3);
    }

    public function showMoney($value, $from = 'USD', $to, $isSymbol = false, $defaultValue = 1)
    {
        $to = Inflector::camelize($to);
        $func = strtolower($from);
        $func = "{$func}To{$to}";
        if (method_exists($this, $func)) {
            $value = $this->$func($value, $defaultValue);
        }
        $value .= $isSymbol ? $this->symbol($to) : strtoupper($to);
        return $value;
    }

    /**
     * @param $value
     * @param bool $isSymbol
     * @param int $defaultValue
     * @return string
     */
    public function showUsdToVnd($value, $isSymbol = false, $defaultValue = 1)
    {
        return $this->showMoney($value, 'USD', 'VND', $isSymbol, $defaultValue);
    }
}
