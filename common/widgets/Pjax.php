<?php

/**
 * pjax
 */

namespace common\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Pjax extends \yii\widgets\Pjax
{

    /**
     * @var array
     */
    public $trigger = [];

    public function registerClientScript()
    {
        parent::registerClientScript();
        foreach ($this->trigger as $plugin) {
            $this->registerPlugin($plugin);
        }
    }

    /**
     * @param $plugin
     */
    protected function registerPlugin($plugin)
    {

        $clientOptions = isset($plugin['clientOptions']) ? $plugin['clientOptions'] : [];
        if (isset($clientOptions['data'])) {
            $this->clientOptions['data'] = ArrayHelper::merge(
                isset($this->clientOptions['data']) ? $this->clientOptions['data'] : [],
                $clientOptions['data']);
            unset($clientOptions['data']);
        }
        $clientOptions['push'] = true;
        $clientOptions = ArrayHelper::merge($this->clientOptions, $clientOptions);
        $jsOptions = isset($plugin['jsOptions']) ? $plugin['jsOptions'] : [];
        $position = isset($jsOptions['position']) ? $jsOptions['position'] : \yii\web\View::POS_END;
        $jsKey = isset($jsOptions['key']) ? $jsOptions['key'] : null;
        if (isset($plugin['selector']) && isset($plugin['event'])) {
            $options = Json::htmlEncode($clientOptions);
            $js = "jQuery('{$plugin['selector']}').on('{$plugin['event']}', function (event) {jQuery.pjax({$options});});";
            $view = $this->getView();
            $view->registerJs($js, $position, $jsKey);
            $view->registerJs("console.log('register plugin: `{$plugin['selector']}` event:`{$plugin['event']}` option: $options')", $position, $jsKey);
        }
    }

}