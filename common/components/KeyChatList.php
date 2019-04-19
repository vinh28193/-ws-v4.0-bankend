<?php


namespace common\components;

use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\FileHelper;

class KeyChatList extends \yii\base\Component
{

    /**
     * @var string|StoreManager
     */
    public $storeManager = 'storeManager';

    public $collection;

    public function getCollection()
    {
        if ($this->collection === null) {
            $this->collection = new ArrayCollection();
            $file = $this->getFileName();
            if (is_file($file) && ($content = file_get_contents($file)) !== false) {
                $content = Json::decode($content, true);
                foreach ($content as $value) {
                    $this->collection->add($value, $value);
                }
            }
        }
        return $this->collection;
    }

    public function save()
    {
        $content = $this->getCollection()->toArray();
        $content = array_keys($content);
        $content = Json::encode($content);
        return file_put_contents($this->getFileName(), $content);
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
        return array_keys($this->getCollection()->toArray());
    }

    public function write($value)
    {
        $this->getCollection()->set($value, $value);
        return $this->save();

    }


    public function remove($key)
    {
        $this->getCollection()->remove($key);
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