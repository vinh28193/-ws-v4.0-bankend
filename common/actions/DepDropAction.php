<?php


namespace common\actions;

use Yii;
use yii\base\InvalidArgumentException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

class DepDropAction extends \yii\base\Action
{

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
    public $depdropParents = 'depdrop_parents';
    public $depdropParam = 'depdrop_params';

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
        if (($parents = ArrayHelper::getValue($_POST, $this->depdropParents)) !== null) {
            $parents = reset($parents);
            $parents = (integer)$parents;
            $out = call_user_func($this->useAction, $parents);
            if (!empty($out)) {
                $selected = '';
                if (($params = ArrayHelper::getValue($_POST, $this->depdropParam)) !== null) {
                    $selected = isset($params[0]) ? $params[0] : $selected;
                }
                return ['output' => $out, 'selected' => $selected];
            }
        }

        return ['output' => '', 'selected' => ''];
    }
}