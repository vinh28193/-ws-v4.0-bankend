<?php

use yii\db\Migration;

/**
 * Class m190516_064122_account_email
 */
class m190516_064122_account_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
                SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `email_account`;
CREATE TABLE `email_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'email',
  `DisplayName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Tên hiện thị',
  `Host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Tên host',
  `Port` int(11) NULL DEFAULT NULL COMMENT 'Cổng ',
  `Username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Tên đăng nhập',
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mật khẩu',
  `EnableSsl` tinyint(1) NULL DEFAULT NULL COMMENT 'Cho phép sử dụng ssl?',
  `UseDefaultCredentials` tinyint(1) NULL DEFAULT NULL,
  `OrganizationId` int(11) NULL DEFAULT NULL,
  `StoreId` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
        ";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190516_064122_account_email cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_064122_account_email cannot be reverted.\n";

        return false;
    }
    */
}
