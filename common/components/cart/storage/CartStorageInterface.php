<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-26
 * Time: 14:49
 */

namespace common\components\cart\storage;

use common\components\cart\CartManager;

interface CartStorageInterface
{


    /**
     * @param $id
     * @return mixed
     */
    public function hasItem($id);

    /**
     * @param $type
     * @param $key
     * @param $value
     * @param $identity
     * @return mixed
     */
    public function addItem($type, $key, $value, $identity);

    /**
     * @param $id
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setItem($id, $key, $value);

    /**
     * @param $filter
     * @param $identity
     * @return mixed
     */
    public function filterItem($filter, $identity);

    /**
     * @param $id
     * @return boolean|mixed
     */
    public function getItem($id);

    /**
     * @param $id
     * @return mixed
     */
    public function removeItem($id);

    /**
     * @param $id
     * @return mixed
     */
    public function setMeOwnerItem($id);

    /**
     * @param $type
     * @param $identity
     * @param null $ids
     * @return mixed
     */
    public function getItems($type, $identity, $ids = null);

    /**
     * @param $type
     * @param $identity
     * @return mixed
     */
    public function countItems($type, $identity);

    /**
     * @param $type
     * @param $identity
     * @param null $keys
     * @return mixed
     */
    public function removeItems($type, $identity, $keys = null);


}