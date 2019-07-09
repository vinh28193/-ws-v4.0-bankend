<?php


namespace common\actions;

use common\components\StoreManager;
use common\models\Order;
use yii\base\Action;
use yii\base\Event;
use yii\di\Instance;
use yii\i18n\Formatter;
use yii\web\Request;

/**
 * Class OrderTrackingCallbackAction
 * @package common\actions
 *
 * @property-read Formatter $formatter;
 * @property-read  StoreManager $storeManager;
 * @property-read  Request $request;
 */
class OrderTrackingCallbackAction extends Action
{

    const EVENT_BEFORE_HANDLE = 'beforeHandle';

    const EVENT_AFTER_HANDLE = 'afterHandle';

    /**
     * @var string|\yii\i18n\Formatter
     */
    public $formatter = 'formatter';

    /**
     * @var string|Request
     */
    public $request = 'request';

    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    public function init()
    {
        parent::init();
        $this->formatter = Instance::ensure($this->formatter, Formatter::className());
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
        $this->request = Instance::ensure($this->request, Request::className());
    }

    public function run()
    {
        $bodyParams = $this->request->bodyParams;

    }

    public function handle()
    {

    }

    public function alertToUser($order, $step)
    {

    }

    /**
     * @param string $shipmentCode
     * @return Order|null
     */
    protected function findOrder($shipmentCode)
    {
        return Order::findOne(1);
    }
}