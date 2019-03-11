<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 13:41
 */

namespace common\components;


use Yii;

/**
 *
 * Class FileCache
 * @package common\components
 */
class FileCache extends \yii\caching\FileCache
{

    /**
     * add [[noCacheParam]] in current request to clear cached with [[key]]
     * @var string
     */
    public $noCacheParam = 'noCache';

    /**
     * the key what [[noCacheParam]] validate with
     * @var string
     */
    public $noCacheValidateKey = 'yess';

    /**
     * @inheritdoc
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        $app = Yii::$app;
        if ($app instanceof \yii\web\Application) {
            $request = $app->getRequest();
            $nocache = $request instanceof \yii\web\Request ? ($request->getIsGet()
                ? $request->get($this->noCacheParam,false)
                : $request->post($this->noCacheParam,false)) : false;
            if ($nocache !== false && $nocache === $this->noCacheValidateKey) {
                $this->delete($key);
            }
        }
        return parent::get($key);
    }
}