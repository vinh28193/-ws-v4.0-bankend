<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 09:24
 */

namespace common\mail;

use Yii;

abstract class BaseTarget extends \yii\base\BaseObject
{
    public $id;

    public $type;
    /**
     * @param Template $template
     * @param string $receive
     */
    public function send($template,$receive){

        // Todo Yii Event After and Before [send()]
        $this->type = $template->type;
        if($this->isActive()){
            $this->prepare($template);
            try {
                $this->handle($receive);
            } catch (\Exception $e) {
                Yii::error($e);
            }
        }


    }

    /**
     * @param $template Template
     * @return mixed
     */
    abstract function prepare($template);
    /**
     * @param $receive string
     * @return mixed
     */
    abstract function handle($receive);

    public function getId(){
        return $this->id;
    }
    public function getType(){
        return $this->type;
    }
    /**
     * @param $type
     * @return bool
     */
    public function isActive(){
        return true;
    }
}