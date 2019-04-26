<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 09:03
 */

namespace common\components\log;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 *  use 3 ways:
 *  1 .Logging::create()->product->push($action, $message, $request, $response)
 *  2. (new Logging())->product->push($action, $message, $request, $response)
 *  3. config as application component:
 *      components => [
 *          'wsLog' => [
 *                 'class' => Logging::className,
 *                  'drivers' => [
 *                      // config driver here
 *                  ]
 *          ],
 *
 *   ]
 * Class Logging
 * @property $drivers array
 * @package common\components\log
 */
class Logging extends \yii\base\Component
{
    /**
     * @var array|LoggingDriverInterface[]
     */
    public $_drivers = [
        'product' => [
            'class' => 'common\components\log\driver\MongoLog',
            'type' => 'Product'
        ],
        'order' => [
            'class' => 'common\components\log\driver\MongoLog',
            'type' => 'Order'
        ],
        'payment' => [
            'class' => 'common\components\log\driver\PaymentLog',
        ],
        'TrackingLog' => [
            'class' => 'common\components\log\driver\TrackingLog',
        ]
    ];


    public function init()
    {
        parent::init();
        $this->drivers = ArrayHelper::merge($this->getDefaultDriver(), $this->drivers);
    }

    /**
     * create object
     * @param $config
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createDriver($config)
    {
        return Yii::createObject($config);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasDriver($name)
    {
        return array_key_exists($name, $this->_drivers);
    }

    /**
     * @param $name
     * @return LoggingDriverInterface|mixed
     * @throws InvalidConfigException
     */
    public function getDriver($name)
    {
        if (!array_key_exists($name, $this->drivers)) {
            throw new \yii\base\InvalidParamException("Unknown driver '{$name}'.");
        }
        $this->setDriver($name, $this->drivers[$name]);

        return $this->drivers[$name];

    }

    /**
     * set a driver
     * @param $name string
     * @param $config string|array|LoggingDriverInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function setDriver($name, $config)
    {
        if (is_array($config) || is_string($config)) {
            $config = $this->createDriver($config);
        }
        if ($config instanceof LoggingDriverInterface) {
            $this->_drivers[$name] = $config;
        } else {
            Yii::warning("can not set: " . get_class($config) . " not instanceof LoggingDriverInterface");
        }
    }

    /**
     * get drivers
     * @return array|LoggingDriverInterface[]
     */
    public function getDrivers()
    {
        return $this->_drivers;
    }


    public function setDrivers($driver)
    {
        $this->_drivers = $driver;
    }

    /**
     * php magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->hasDriver($name)) {
            return $this->getDriver($name);
        }
        return parent::__get($name);
    }

    /**
     * php magic method isset
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_drivers[$name]) || parent::__isset($name);
    }


    /**
     * call an object instance
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public static function create()
    {
        return Yii::createObject(get_called_class());
    }

    /**
     * @param $driver string driver id
     * @return LoggingDriverInterface
     * @throws InvalidConfigException
     */
    protected function resolveDriver($driver)
    {
        if (($driver = $this->getDriver($driver)) === null) {
            throw new InvalidConfigException("Not found config for driver `$driver`");
        }
        if (!$driver instanceof LoggingDriverInterface) {
            throw new InvalidConfigException("Driver `" . get_class($driver) . "` not  instance of `LoggingDriverInterface`");
        }
        return $driver;
    }

    public function getDefaultDriver()
    {
        return [
            'file' => [
                'class' => 'common\components\log\driver\SaveFileLog'
            ]
        ];
    }

    protected function handlePushFail($driver, $action, $message, $params)
    {
        /** @var  $fileDriver LoggingHandleDriverFailInterface | LoggingDriverInterface */
        if (!($fileDriver = $this->resolveDriver('file')) instanceof LoggingHandleDriverFailInterface) {
            Yii::warning("Only driver instance of `LoggingHandleDriverFailInterface` can be handle when other driver push failed");
            return;
        }
        $fileDriver->resolve($driver);
        $fileDriver->pushData($action, $message, $params);
    }

    protected function handlePullFail($driver, $condition)
    {
        /** @var  $fileDriver LoggingHandleDriverFailInterface | LoggingDriverInterface */
        if (!($fileDriver = $this->resolveDriver('file')) instanceof LoggingHandleDriverFailInterface) {
            Yii::warning("Only driver instance of `LoggingHandleDriverFailInterface` can be handle when other driver pull failed");
            return;
        }
        try {
            $fileDriver->resolve($driver);
            $fileDriver->pullData($condition);
        } catch (LoggingHandleDriverException $exception) {
            Yii::error($exception);
            return;
        }

    }

    /**
     * push a log
     * @param $driver string driver ID
     * @param $action string
     * @param $message string
     * @param $params array
     * Phòng trường hợp Mongo dịch vu bị lỗi thì lưu xuống file @Phuchc 03/04/2019
     */
    public function push($driver, $action, $message, $params)
    {
        try {
            $driver = $this->resolveDriver($driver);
            $driver->pushData($action, $message, $params);
        } catch (\Exception $exception) {
            Yii::error($exception);
            $this->handlePushFail($driver, $action, $message, $params);
        }
    }

    /**
     * @param $driver
     * @param $condition
     */
    public function pull($driver, $condition)
    {

    }
}
