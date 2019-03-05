<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 13:52
 */

namespace common\rbac\controllers;

use Yii;
use yii\di\Instance;
use yii\helpers\Console;
use yii\rbac\DbManager;

class RbacController extends \yii\console\Controller
{

    public $applyAction = 'up';

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['applyAction']);
    }

    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), ['aa' => 'applyAction']);
    }


    /**
     * @var string | \yii\rbac\BaseManager
     */
    public $authManager = 'authManager';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->authManager = Instance::ensure($this->authManager, DbManager::className());
        parent::init();
    }

    public function actionMigrate()
    {
        $migrateController = [
            'class' => \yii\console\controllers\MigrateController::className(),
            'migrationPath' => ['@yii/rbac/migrations']
        ];
        /** @var  $migrateController \yii\console\controllers\MigrateController */
        $migrateController = Yii::createObject($migrateController, ['migrate', $this]);
        Yii::$app->controller = $migrateController;
        Yii::$app->controller->runAction($this->applyAction, $this->applyAction === 'up' ? [] : ['3']);
    }


    public function actionCreateDefault()
    {
        $this->authManager->removeAll();
        $this->createDefaultRoles();
        $this->createDefaultPermission();
    }

    protected function createDefaultRoles()
    {
        $roles = [
            'admin', 'warehouse', 'purchase',
            'sale', 'master_sale',
            'operation', 'master_operation',
            'marketing', 'master_marketing',
        ];
        $mapRoles = [
            'admin' => ['warehouse', 'purchase', 'sale', 'master_sale', 'operation', 'master_operation', 'marketing', 'master_marketing'],
            'master_sale' => ['sale'],
            'master_operation' => ['operation'],
            'master_marketing' => ['marketing'],
        ];
        foreach ($roles as $name) {
            $this->stdout("creating role $name ... \n", Console::FG_GREEN);
            $role = $this->authManager->createRole($name);
            $this->authManager->add($role);
            $this->stdout("added role $name ... \n", Console::FG_GREEN);
        }
        foreach ($mapRoles as $roleName => $mappingRoles){
            $this->stdout("creating child for role $roleName ... \n", Console::FG_GREEN);
            $role = $this->authManager->getRole($roleName);
            foreach ($mappingRoles as $name){
                $childRole =  $this->authManager->getRole($name);
                $this->authManager->addChild($role,$childRole);
                $this->stdout("added child $name for parent role  $roleName... \n", Console::FG_GREEN);
            }
        }
        $adminRole =  $this->authManager->getRole('admin');
        $this->authManager->assign($adminRole,1);
        $salenRole =  $this->authManager->getRole('sale');
        $this->authManager->assign($salenRole,2);
    }

    protected function createDefaultPermission()
    {
        $permissions = [
            'canCreate' => 'Can be create something',
            'canUpdate' => 'Can be update something',
            'canView' => 'Can be view something',
            'canDelete' => 'Can be delete something',
        ];
        $mapPermissions = [
            'admin' => ['canCreate', 'canUpdate', 'canView', 'canDelete'],
            'sale' => ['canCreate', 'canView', 'canUpdate'],
            'master_sale' => ['canCreate', 'canView', 'canUpdate'],
            'operation' => ['canCreate', 'canView', 'canUpdate'],
            'master_operation' => ['canCreate', 'canView', 'canUpdate'],
            'marketing' => ['canCreate', 'canView', 'canUpdate'],
            'master_marketing' => ['canCreate', 'canView', 'canUpdate'],
            'warehouse' => ['canCreate', 'canView', 'canUpdate'],
            'purchase' => ['canCreate', 'canView', 'canUpdate']
        ];

        foreach ($permissions as $name => $description ) {
            $this->stdout("creating permissions $name ... \n", Console::FG_GREEN);
            $permission = $this->authManager->createPermission($name);
            $permission->description = $description;
            $this->authManager->add($permission);
            $this->stdout("added permission $name ... \n", Console::FG_GREEN);
        }
        foreach ($mapPermissions as $roleName => $mappingPermissions){
            $this->stdout("creating child for role $roleName ... \n", Console::FG_GREEN);
            $role = $this->authManager->getRole($roleName);
            foreach ($mappingPermissions as $name){
                $childPermission =  $this->authManager->getPermission($name);
                $this->authManager->addChild($role,$childPermission);
                $this->stdout("added child  permission $name for role  $roleName... \n", Console::FG_GREEN);
            }
        }
    }

}