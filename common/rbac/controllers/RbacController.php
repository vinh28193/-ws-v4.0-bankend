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

    /**
     * Runs Migrate : Create all table rabc you needs.
     * @return null
     **/
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

    /**
     * Runs Test RABC Create role + assign role for user id.
     * @return null
     **/
    public function actionInit()
    {
        /*
            $auth = Yii::$app->authManager;
            $auth->removeAll();


            // add "createOrder" permission
            $createOrder = $auth->createPermission('createOrder');
            $createOrder->description = 'Create a order';
        //        $createOrder->ruleName = 'createOrder';
        //        $createOrder->data = '';
            $auth->add($createOrder);

            // add "updateOrder" permission
            $updateOrder = $auth->createPermission('updateOrder');
            $updateOrder->description = 'Update a order';
        //        $updateOrder->ruleName = 'updateOrder';
        //        $updateOrder->data = '';
            $auth->add($updateOrder);

            // add "deleteOrder" permission
            $deleteOrder = $auth->createPermission('deleteOrder');
            $deleteOrder->description = 'Delete a order or Owner is delete ';
        //        $deleteOrder->ruleName = 'deleteOrder';
        //        $deleteOrder->data = '';
            $auth->add($deleteOrder);


            // add "author_tester" role and give this role the "createOrder" permission
            $author_tester = $auth->createRole('userTester');
            $author_tester->description = "userTester add more script test unit or funtion + acception ";
        //        $author_tester->ruleName = 'userTester';
        //        $author_tester->data = '';
            $auth->add($author_tester);
            $auth->addChild($author_tester, $createOrder);

            // add "admin" role and give this role the "updateOrder" permission
            // as well as the permissions of the "author" role
            $superadmin = $auth->createRole('superAdmin');
            $superadmin->description = "Super Admin all access role + action ";
        //        $superadmin->ruleName = 'superAdmin';
        //        $superadmin->data = '';
            $auth->add($superadmin);

            $auth->addChild($superadmin, $updateOrder);
            $auth->addChild($superadmin, $author_tester);
            $auth->addChild($superadmin, $deleteOrder);

            // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
            // usually implemented in your User model.
            $auth->assign($author_tester, 7);
            $auth->assign($superadmin, 13);
            */

    }

    /**
     * Runs CreateDefault removeAll , DefaultRoles , DefaultPermission
     * @return null
     **/
    public function actionCreateDefault()
    {
        $this->authManager->removeAll();
        $this->createDefaultRoles();
        $this->createDefaultPermission();
    }

    /**
     * Runs Create DefaultRoles + assign role for user id.
     * @return null
     **/
    protected function createDefaultRoles()
    {
        $roles = [
            'superAdmin' => 'Super Admin all access role + action',
            'admin' => 'All action master_operation , master_marketing , master_sale  + assign role for user We shop',
            'master_marketing' => 'All action master_marketing + assign role for marketing',
            'master_accountant' => 'All action master_accountant + assign role for accountant',
            'master_operation' => 'All action master_operation  + assign role for marketing',
            'warehouse' => 'Role for warehouse < master_operation ',
            'purchase' => 'Role for purchase < master_operation ',
            'sale' => 'Role for sale < master_sale ',
            'master_sale' => 'All action master_sale  + assign role for maketing',
            'operation' => 'Role for operation < master_marketing',
            'marketing_intent' => 'Role for marketing_intent < master_marketing',
            'marketing_ads' => 'Role for marketing_ads < master_marketing',
            'accountant' => 'Role for accountant < master_accountant',
            'tester' => 'Tester add more script test unit or function + acception',
            'marketing' => 'Role for marketing < master_marketing',
        ];
        $mapRoles = [
            'master_sale' => ['sale'],
            'master_operation' => ['operation' ,'warehouse' , 'purchase' ],
            'master_marketing' => ['marketing','marketing_intent','marketing_ads' ],
            'master_accountant' => ['accountant'],
            'admin' => [ 'marketing_intent','marketing_ads' ,'accountant','master_accountant' , 'tester' ,'warehouse', 'purchase', 'sale', 'master_sale', 'operation', 'master_operation', 'marketing', 'master_marketing'],
            'superAdmin' => [ 'marketing_intent','marketing_ads' , 'accountant','master_accountant','tester' , 'admin','warehouse', 'purchase', 'sale', 'master_sale', 'operation', 'master_operation', 'marketing', 'master_marketing'],
        ];
        foreach ($roles as $nameRole => $description ) {
            $this->stdout("creating role $nameRole ... \n", Console::FG_GREEN);
            $role = $this->authManager->createRole($nameRole);
            $role->description = $description;
            $this->authManager->add($role);
            $this->stdout("added role $nameRole ... \n", Console::FG_GREEN);
        }
        foreach ($mapRoles as $roleName => $mappingRoles){
            $this->stdout("creating child for role $roleName ... \n", Console::FG_GREEN);
            $role = $this->authManager->getRole($roleName);
                foreach ($mappingRoles as $name){
                    $childRole =  $this->authManager->getRole($name);
                    $this->authManager->addChild($role,$childRole);
                    $this->stdout("added child Role $name for parent role  $roleName ... \n", Console::FG_GREEN);
                }
        }

        // Super Admin
        $super_admin =  $this->authManager->getRole('superAdmin');
        $this->authManager->assign($super_admin,13);

        // Master Sales
        $master_sale =  $this->authManager->getRole('master_sale');
        $this->authManager->assign($master_sale,1);

        // Master Operation
        $master_operation =  $this->authManager->getRole('master_operation');
        $this->authManager->assign($master_operation,2);

        // Master Marketing
        $master_marketing =  $this->authManager->getRole('master_marketing');
        $this->authManager->assign($master_marketing,3);

        // Master Accountant
        $master_accountant =  $this->authManager->getRole('master_accountant');
        $this->authManager->assign($master_accountant,4);


        // warehouse
        $warehouse =  $this->authManager->getRole('warehouse');
        $this->authManager->assign($warehouse,5);

        // purchase
        $purchase =  $this->authManager->getRole('purchase');
        $this->authManager->assign($purchase,6);

        // sale
        $sale =  $this->authManager->getRole('sale');
        $this->authManager->assign($sale,7);

        //operation 8
        $operation =  $this->authManager->getRole('operation');
        $this->authManager->assign($operation,8);

        //marketing_intent 9
        $marketing_intent =  $this->authManager->getRole('marketing_intent');
        $this->authManager->assign($marketing_intent,9);

        // marketing_ads 10
        $marketing_ads =  $this->authManager->getRole('marketing_ads');
        $this->authManager->assign($marketing_ads,10);

        // accountant 11
        $accountant =  $this->authManager->getRole('accountant');
        $this->authManager->assign($accountant,11);

        // tester 12
        $tester =  $this->authManager->getRole('tester');
        $this->authManager->assign($tester,12);

        // marketing 14
        $marketing =  $this->authManager->getRole('marketing');
        $this->authManager->assign($marketing,14);

    }

    protected function createDefaultPermission()
    {
        $permissions = [
            'CanIndex' => 'Can get list all something',
            'canCreate' => 'Can be create something',
            'canUpdate' => 'Can be update something',
            'canView' => 'Can be view detail by id something',
            'canDelete' => 'Can be delete something',
        ];
        $mapPermissions = [
            'admin' => ['CanIndex','canCreate', 'canUpdate', 'canView', 'canDelete'],
            'sale' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'master_sale' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'operation' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'master_operation' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'marketing' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'master_marketing' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'warehouse' => ['CanIndex','canCreate', 'canView', 'canUpdate'],
            'purchase' => ['CanIndex','canCreate', 'canView', 'canUpdate']
        ];

        foreach ($permissions as $namePermissions => $description ) {
            $this->stdout("creating permissions $namePermissions ... \n", Console::FG_GREEN);
            $permission = $this->authManager->createPermission($namePermissions);
            $permission->description = $description;
            $this->authManager->add($permission);
            $this->stdout("added permission $namePermissions ... \n", Console::FG_GREEN);
        }
        foreach ($mapPermissions as $roleName => $mappingPermissions){
            $this->stdout("creating child for role $roleName ... \n", Console::FG_GREEN);
            $role = $this->authManager->getRole($roleName);
            foreach ($mappingPermissions as $nameMapPermissions){
                $childPermission =  $this->authManager->getPermission($nameMapPermissions);
                $this->authManager->addChild($role,$childPermission);
                $this->stdout("added child  permission $nameMapPermissions for role  $roleName... \n", Console::FG_GREEN);
            }
        }
    }



}
