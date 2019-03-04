<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 09:12
 */

namespace common\components\consoles;


class StoreManager extends \common\components\StoreManager
{

    public $domainAttribute = 'url';

    public function init()
    {
        $_SERVER['SERVER_NAME'] = $this->defaultDomain;
        parent::init();

    }

    public function getDomain()
    {
        if($this->getStore() !== null){
          return $this->getStore()->{$this->domainAttribute};
        }
        return parent::getDomain();
    }
}