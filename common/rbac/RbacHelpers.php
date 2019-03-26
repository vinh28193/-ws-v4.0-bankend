<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 13:28
 */

class RbacHelpers
{
    /**
     * Roles
     */
    // Group 1; for user
    const ROLE_USER = 'user';
    // Group 2; for Admin
    const ROLE_SUPER_ADMIN = 'superAdmin';
    const ROLE_ADMIN = 'admin';
    // Group 3
    const ROLE_WAREHOUSE = 'warehouse';
    const ROLE_PURCHASE = 'purchase';
    const ROLE_TESTER = 'tester';
    // Group 3 for sale
    const ROLE_SALE = 'sale';
    const ROLE_MASTER_SALE = 'master_sale';
    // Group 3 for operation
    const ROLE_OPERATION = 'operation';
    const ROLE_MASTER_OPERATION = 'master_operation';
    // Group 3 for marketing
    const ROLE_MARKETING = 'marketing';
    const ROLE_MARKETING_INTENT = 'marketing_intent';
    const ROLE_MARKETING_ADS = 'marketing_ads';
    const ROLE_MASTER_MARKETING = 'master_marketing';
    // Group 3 for accountant
    const ROLE_ACCOUNTANT = 'accountant';
    const ROLE_MASTER_ACCOUNTANT = 'master_accountant';

    /**
     * Permissions
     */

    const PERMISSIONS_CAN_CREATE = 'canCreate';

    /**
     * @return \yii\rbac\ManagerInterface
     */
    public static function getAuthManager()
    {
        return Yii::$app->authManager;
    }

    /**
     * @param $roles
     */
    public static function getIdentityByRoles($roles){
        $ids = [];
        foreach ($roles as $role){
            $ids += self::getAuthManager()->getUserIdsByRole($role);
        }
        $class = Yii::$app->getUser()->identityClass;
        return $class::find()->where(['id' => $ids])->all(Yii::$app->db);
    }
    /**
     * @return array
     */
    public static function getDefaultRoles()
    {
        return [
            self::ROLE_SUPER_ADMIN => [
                'description' => 'all action',
                'child' => [
                    self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_WAREHOUSE, self::ROLE_PURCHASE, self::ROLE_TESTER,
                    self::ROLE_SALE, self::ROLE_MASTER_SALE,
                    self::ROLE_OPERATION, self::ROLE_MASTER_OPERATION,
                    self::ROLE_MARKETING, self::ROLE_MARKETING_INTENT, self::ROLE_MARKETING_ADS, self::ROLE_MASTER_MARKETING,
                    self::ROLE_ACCOUNTANT, self::ROLE_MASTER_ACCOUNTANT,
                ]
            ],
        ];
    }

    public static function getDefaultPermissions()
    {
        return [
            self::PERMISSIONS_CAN_CREATE => [
                'description' => 'can create some thing',
                'roles' => [
                    self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_WAREHOUSE, self::ROLE_PURCHASE, self::ROLE_TESTER,
                    self::ROLE_SALE, self::ROLE_MASTER_SALE,
                    self::ROLE_OPERATION, self::ROLE_MASTER_OPERATION,
                    self::ROLE_MARKETING, self::ROLE_MARKETING_INTENT, self::ROLE_MARKETING_ADS, self::ROLE_MASTER_MARKETING,
                    self::ROLE_ACCOUNTANT, self::ROLE_MASTER_ACCOUNTANT,
                ]
            ]
        ]
    }
}