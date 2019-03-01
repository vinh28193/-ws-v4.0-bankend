<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 10:03
 */

namespace weshop\payment\traits;

use weshop\payment\BasePaymentMethod;
use weshop\payment\BasePaymentProvider;

/**
 * Trait OwnerTrait
 * @property BasePaymentProvider | BasePaymentMethod $owner
 */
trait OwnerTrait
{

    private $_owner;

    public function getOwner(){
        return $this->_owner;
    }

    public function setOwner($owner){
        $this->_owner = $owner;
    }
}