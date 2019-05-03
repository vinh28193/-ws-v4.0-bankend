<?php


namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\web\Request;

class LinkableWidget extends Widget
{

    /**
     * @var \common\data\Filter
     */
    public $filter;

    /**
     * @var array HTML attributes for the sorter container tag.
     * @see \yii\helpers\Html::ul() for special attributes.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */

    public $options = [];

    /**
     * @var array HTML attributes for the link in a sorter container tag which are passed to [[Sort::link()]].
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.6
     */
    public $linkOptions = [];

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        if (($params = $this->params) === null) {
            $request = Yii::$app->getRequest();
            $params = $request instanceof Request ? $request->getQueryParams() : [];
        }
        parent::run();
    }
}