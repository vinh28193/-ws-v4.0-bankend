<?php
namespace common\components;

use Rediska;

Class RedisQueue
{
    public $rediska;
    public $_name;
    public function __construct($name)
    {
        $this->_name = $name;
        $password = '';
        if(isset(\Yii::$app->params['redis_queue']['password'])){
            $password = \Yii::$app->params['redis_queue']['password'];
        }
        $options = array(
            'servers' => array(
                array('host' => \Yii::$app->params['redis_queue']['host'],'port' => \Yii::$app->params['redis_queue']['port'],'password' => $password),
//                'exampleAlias' => array('host' => '127.0.0.1'),
//                array('host' => '127.0.0.1', 'alias' => 'exampleAlias2'),
            )
        );
        $this->rediska = new Rediska($options);
    }


    public function pop()
    {
        return $this->rediska->popFromList($this->_name);
    }

    public function push($item)
    {
        return $this->rediska->prependToList($this->_name, $item);
    }

    public function getAllQueueItem() {
        return $this->rediska->getList($this->_name);
    }

    public function truncateList($key, $start=0, $end=-1){
        return $this->rediska->truncateList($key, $start, $end);
    }

    public function flush(){
        return $this->rediska->flushDb($this->_name);
    }

    public function count(){
        //return $this->rediska->getKeysCount($this->_name);
        return $this->rediska->getListLength($this->_name);
    }

//    public function shutdown(){
//        return $this->rediska->shutdown($this->_name);
//    }

}

?>