<?php

use yii\db\Migration;

/**
 * Class m190516_062909_queuedEmail
 */
class m190516_062909_queuedEmail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
                SET NAMES utf8mb4;
                SET FOREIGN_KEY_CHECKS = 0;
                DROP TABLE IF EXISTS `queued_email`;
                CREATE TABLE `queued_email`  (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `Priority` int(11) NULL DEFAULT NULL,
                  `From` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `FromName` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `To` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `ToName` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `CC` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `Bcc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `Subject` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `Body` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
                  `CreatedTime` datetime(0) NULL DEFAULT NULL,
                  `SentTries` int(11) NULL DEFAULT 0,
                  `SentOn` datetime(0) NULL DEFAULT NULL,
                  `EmailAccountId` int(11) NULL DEFAULT NULL,
                  `CampaignId` int(11) NULL DEFAULT NULL,
                  `TemplateId` int(11) NULL DEFAULT NULL,
                  `RecipientId` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `Opened` tinyint(1) NULL DEFAULT NULL,
                  `Openedon` datetime(0) NULL DEFAULT NULL,
                  `Status` char(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0',
                  `OrderId` int(11) NULL DEFAULT NULL,
                  `api_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
                  `Bounce` tinyint(1) NULL DEFAULT NULL,
                  `Clicked` tinyint(1) NULL DEFAULT NULL,
                  `Sent` tinyint(1) NULL DEFAULT NULL,
                  `Using` tinyint(1) NULL DEFAULT 0 COMMENT 'Flag select email send',
                  `OrderType` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'loai order',
                  `StatusV3` tinyint(1) NULL DEFAULT 0,
                  PRIMARY KEY (`id`) USING BTREE,
                  INDEX `QueuedEmail_EmailAccount`(`EmailAccountId`) USING BTREE,
                  INDEX `TemplateId`(`TemplateId`) USING BTREE,
                  INDEX `CampaignId`(`CampaignId`) USING BTREE,
                  INDEX `OrderId`(`OrderId`) USING BTREE,
                  INDEX `Priority`(`Priority`) USING BTREE,
                  INDEX `Status`(`Status`) USING BTREE,
                  CONSTRAINT `queued_email_ibfk_1` FOREIGN KEY (`EmailAccountId`) REFERENCES `email_account` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
                  CONSTRAINT `queued_email_ibfk_4` FOREIGN KEY (`OrderId`) REFERENCES `order` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
                ) ENGINE = InnoDB AUTO_INCREMENT = 344 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
                
                SET FOREIGN_KEY_CHECKS = 1;
        ";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190516_062909_queuedEmail cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_062909_queuedEmail cannot be reverted.\n";

        return false;
    }
    */
}
