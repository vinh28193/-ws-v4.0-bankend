<?php
/**
 * Created by PhpStorm.
 * User: HUYKAKA
 * Date: 3/26/2019
 * Time: 4:30 PM
 */

namespace common\actions;

use common\models\User;
use yii\di\Instance;

class SaleAction extends \yii\base\Action
{

    /**
     * cache duration(s)
     */
    const CACHE_DURATION = 3600;

    /**@var \yii\caching\CacheInterface|array|string */
    public $cache = 'cache';

    /**
     * @var string | \yii\rbac\BaseManager
     */
    public $authManager = 'authManager';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager,\yii\rbac\DbManager ::className());
        $this->cache = Instance::ensure($this->cache, 'yii\caching\CacheInterface');
    }

    public function run(){
        $id1 = $this->authManager->getUserIdsByRole('sale');
        $id2 = $this->authManager->getUserIdsByRole('master.sale');
        $id3 = $this->authManager->getUserIdsByRole('master_sale');
        return User::find()->select(['id', 'username', 'email', 'employee'])
            ->where(['or',
                ['id' => $id1],
                ['id' => $id2],
                ['id' => $id3],
            ])
            ->asArray()->all();

    }
}