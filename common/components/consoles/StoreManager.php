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

    public $defaultDomain = 'weshop-4.0.frontend.vn';

    public function init()
    {
        $_SERVER['SERVER_NAME'] = $this->defaultDomain;
        parent::init();

    }

    public function getDomain()
    {
        if($this->_store !== null){
            try {
                $domain = $this->_store->{$this->domainAttribute};
                return $domain;
            }
            catch (\Exception $e){
                return $this->defaultDomain;
            }
        }
        return parent::getDomain();
    }
}