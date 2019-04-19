<?php


namespace common\components;

use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\FileHelper;

class KeyChatList extends \yii\base\Component
{

    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    public $_collections = [];

    public function load()
    {
        if (empty($this->_collections)) {
            $file = $this->getFileName();
            if (is_file($file) && ($content = file_get_contents($file)) !== false) {
                $content = Json::decode($content, true);
                $this->_collections = $content;
            }
        }
    }

    public function save()
    {
        return file_put_contents($this->getFileName(), Json::encode(array_values($this->_collections)));
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
    }

    /**
     * @return array|bool|false|mixed|string
     */
    public function read()
    {
        $this->load();
        return $this->_collections;
    }

    public function has($value)
    {
        $this->load();
        return in_array($value, array_values($this->_collections));
    }

    public function write($value)
    {
        $this->load();
        if (!$this->has($value)) {
            array_unshift($this->_collections, $value);
            $this->save();
        }
        return false;

    }


    public function remove($index)
    {
        $this->load();
        if (isset($this->_collections[$index])) {
            unset($this->_collections[$index]);
            return $this->save();
        }
        return false;
    }

    public function clear(){
        $this->_collections = [];
        return $this->save();
    }


    protected function getFileName()
    {
        $fileName = "{$this->storeManager->getDomain()}.json";
        $fileName = Yii::getAlias('@webroot/listchats/') . $fileName;
        $filePath = dirname($fileName);
        FileHelper::createDirectory($filePath, 0777, true);
        return $fileName;
    }
}