<?php


namespace common\actions;

use Yii;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class DepDropAction extends \yii\base\Action
{

    /**
     * @var bool
     */
    public $defaultSelect = false;
    /**
     * @var string
     */
    public $selectedParam = 'id';
    /**
     *  function foo($parents) {
     *     // compute $newValue here
     *     return $value;
     * }
     * @var callable|string
     */
    public $useAction;
    /**
     * @var string
     */
    public $depdropParam = 'depdrop_parents';

    /**
     * cache duration(s)
     */
    const CACHE_DURATION = 3600;

    /**@var \yii\caching\CacheInterface|array|string */
    public $cache = 'cache';


    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
        if ($this->useAction === null) {
            throw  new InvalidArgumentException(get_class($this) . " missing required parameter `useAction`");
        }
    }

    public function run()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (($parents = ArrayHelper::getValue($_POST, $this->depdropParam)) !== null) {
            $parents = reset($parents);
            $out = call_user_func($this->useAction, $parents);
            if (!empty($out)) {
                return ['output' => $out, 'selected' => $this->defaultSelect && count($out) > 0 ? $out[0]['id'] : ''];
            }
        }

        return ['output' => '', 'selected' => ''];
    }
}