<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-27
 * Time: 09:43
 */

namespace common\components\cart\storage;


use yii\helpers\ArrayHelper;

class SessionCartStorage extends \yii\base\BaseObject implements CartStorageInterface
{


    public $sessionName = 'SessionCartStorage';

    public function getSession()
    {
        return \Yii::$app->session;
    }

    public function setByKey($key, $value, $default = false)
    {
        list($key, $id) = $key;
        $values = $this->getSession()->get($id, $default);
        if ($values === $default) {
            $values = [$key => $value];
        } else {
            $values[$key] = $values;
        }

        $this->getSession()->set($this->sessionName, [$id => $values]);
        return true;
    }

    public function hasItem($key)
    {

        list($key, $id) = $key;
        $keys = array_keys($this->getItems($id));
        return in_array($key, $keys);

    }

    public function addItem($key, $value)
    {
        return $this->setItem($key, $value);
    }

    public function setItem($key, $value)
    {
        list($key, $id) = $key;
        $values = $this->getItems($id);
        $this->removeItems($id);
        $values = ArrayHelper::merge($values, [
            $key => $value
        ]);
        $this->getSession()->set($this->sessionName, [$id => $values]);
    }

    public function getItem($key)
    {
        list($key, $id) = $key;
        return ArrayHelper::getValue($this->getItems($id), $key, false);
    }

    public function removeItem($key)
    {
        list($key, $id) = $key;
        $values = $this->getItems($id);
        $this->removeItems($id);
        if (isset($values[$key])) {
            unset($values[$key]);
        }
        $this->getSession()->set($this->sessionName, [$id => $values]);
    }

    public function getItems($identity)
    {
        return ArrayHelper::getValue($this->getSession()->get($this->sessionName, []), $identity, []);
    }

    public function countItems($identity)
    {
        return count($this->getItems($identity));
    }

    public function removeItems($identity)
    {
        $items = $this->getSession()->get($this->sessionName, []);
        if (isset($items[$identity])) {
            unset($items[$identity]);
        }
        return $this->getSession()->set($this->sessionName, $items);
    }
}