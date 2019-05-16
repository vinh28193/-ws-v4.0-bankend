<?php

use yii\db\Migration;

/**
 * Class m190515_044109_wallet
 */
class m190515_044109_wallet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
            SET NAMES utf8mb4;
            SET FOREIGN_KEY_CHECKS = 0;
            DROP TABLE IF EXISTS `wallet_client`;
            CREATE TABLE `wallet_client`  (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `auth_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `customer_id` int(11) NOT NULL,
              `customer_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `customer_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `current_balance` double(18, 2) NOT NULL DEFAULT 0.00 COMMENT 'Tổng số dư hiện tại (=freeze_balance+usable_balance)',
              `freeze_balance` double(18, 2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền bị đóng băng hiện tại',
              `usable_balance` double(18, 2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền có thể sử dụng để thanh toán',
              `withdrawable_balance` double(18, 2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền có thể sử dụng để rút khỏi ví',
              `total_refunded_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Tổng số tiền được refund',
              `total_topup_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Tổng số tiền đã nạp',
              `total_using_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Tổng số tiền đã thanh toán đơn hàng',
              `total_withdraw_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Tổng số tiền đã rút',
              `last_refund_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'số tiền được refund lần cuôi',
              `last_refund_at` datetime(0) NULL DEFAULT NULL COMMENT 'thời gian refund lần cuối',
              `last_topup_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Số tiền nạp lần cuôi',
              `last_topup_at` datetime(0) NULL DEFAULT NULL COMMENT 'thời gian nạp lần cuối',
              `last_using_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Số tiền giao dịch lần cuối',
              `last_using_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian thực hiện giao dịch cuối cùng',
              `last_withdraw_amount` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Số tiền rút lần cuối',
              `last_withdraw_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian rút lần cuối',
              `current_bulk_point` int(11) NULL DEFAULT 0 COMMENT 'Số điểm bulk hiện tại',
              `current_bulk_balance` double(18, 2) NULL DEFAULT 0.00 COMMENT 'Số tiền được quy đổi bulk hiện tại',
              `otp_veryfy_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mã xác thực otp hiện tại',
              `otp_veryfy_expired_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian hết hạn mã otp',
              `otp_veryfy_count` int(1) NULL DEFAULT NULL COMMENT 'Tổng số mã xác thực đã sử dụng',
              `store_id` int(11) NULL DEFAULT NULL,
              `created_at` datetime(0) NULL DEFAULT NULL,
              `updated_at` datetime(0) NULL DEFAULT NULL,
              `identity_number` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `identity_issued_date` date NULL DEFAULT NULL,
              `identity_issued_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `identity_image_url_before` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `identity_verified` tinyint(1) NULL DEFAULT 0,
              `identity_image_url_after` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `status` int(255) NULL DEFAULT NULL COMMENT '0:inactive;1active;2:freeze',
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
            DROP TABLE IF EXISTS `wallet_log`;
            CREATE TABLE `wallet_log`  (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `walletTransactionId` int(11) NOT NULL,
              `TypeTransaction` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT ' TOPUP - nạp tiền\r\nREFUN - nạp tiền do refun\r\nWITHDRAW - rút tiền\r\nFREEZE - đóng băng\r\nUNFREEZEADD - mở đóng băng cộng\r\nUNFREEZEREDUCE - mở đóng băng trừ\r\nPAYMENT - thanh toán ',
              `walletId` int(11) NOT NULL,
              `typeWallet` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'CLIENT - ví client\r\nMERCHANT - ví merchant',
              `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `amount` decimal(18, 2) NOT NULL DEFAULT 0.00 COMMENT 'số tiền giao dịch',
              `BeforeAccumulatedBalances` decimal(18, 2) NULL DEFAULT NULL COMMENT 'số dư trước khi giao dịch - theo field current_balance\r\n',
              `AfterAccumulatedBalances` decimal(18, 2) NULL DEFAULT NULL COMMENT 'số dư sau khi giao dịch - theo field current_balance',
              `createDate` datetime(0) NOT NULL,
              `storeId` int(11) NULL DEFAULT NULL,
              `status` int(1) NOT NULL DEFAULT 0 COMMENT '0- Pending ; 1 - Success; 2 - Fail',
              PRIMARY KEY (`id`) USING BTREE,
              INDEX `index-wallet-transaction`(`walletTransactionId`) USING BTREE,
              CONSTRAINT `foreignkey-wallet-transaction` FOREIGN KEY (`walletTransactionId`) REFERENCES `wallet_transaction` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
            ) ENGINE = InnoDB AUTO_INCREMENT = 1419 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
            DROP TABLE IF EXISTS `wallet_merchant`;
            CREATE TABLE `wallet_merchant`  (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `account_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Mã ví - Ma tien to: S(danh cho master)  , nội bộ ',
              `account_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Email ',
              `account_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `account_bank_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `parent_account_id` int(11) NULL DEFAULT NULL,
              `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'mo ta ve tai khoan',
              `opening_balance` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Số dư mở ví',
              `current_balance` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Số dư hiện tại',
              `total_credit_amount` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Tổng số giao dịch phát sinh tăng',
              `total_debit_amount` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Tổng số giao dịch phát sinh giảm',
              `previous_current_balance` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Số dư kỳ trước ',
              `last_amount` decimal(18, 2) NULL DEFAULT NULL,
              `last_updated` datetime(0) NULL DEFAULT NULL,
              `last_edit_user_id` int(11) NULL DEFAULT NULL,
              `note` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `store_id` int(11) NULL DEFAULT NULL,
              `active` tinyint(1) NULL DEFAULT NULL,
              `account_ref_payment_mapping` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mã tài khoản ngân lượng / IdoMog mapping tùy thuộc vào StoreId',
              `payment_provider_id` int(11) NULL DEFAULT NULL,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
            DROP TABLE IF EXISTS `wallet_transaction`;
            CREATE TABLE `wallet_transaction`  (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `wallet_transaction_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `wallet_client_id` int(11) NOT NULL,
              `wallet_merchant_id` int(11) NOT NULL,
              `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'TOP_UP/FREEZE/UN_FREEZE/PAY_ORDER/REFUND/WITH_DRAW',
              `order_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mã thanh toán (order, addfee)',
              `status` int(11) NULL DEFAULT NULL COMMENT '0:Queue//1:processing//2:complete//3:cancel//4:fail',
              `amount` double(18, 2) NULL DEFAULT NULL,
              `credit_amount` double(18, 2) NULL DEFAULT NULL COMMENT 'Số tiền nạp vào tài khoản khách(Topup,refund...)',
              `debit_amount` double(18, 2) NULL DEFAULT NULL COMMENT 'Số tiền khách thanh toán',
              `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mô tả giao dịch',
              `verify_receive_type` int(11) NULL DEFAULT NULL COMMENT 'Kieu xac thuc 0:phone,1:email',
              `verify_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'OTP code',
              `verify_count` int(11) NULL DEFAULT NULL,
              `verify_expired_at` int(11) NULL DEFAULT NULL,
              `verified_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thoi gian xac thuc otp',
              `refresh_count` int(11) NULL DEFAULT NULL,
              `refresh_expired_at` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `create_at` datetime(0) NOT NULL,
              `update_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
              `complete_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thoi gian giao dich thanh cong',
              `cancel_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thoi gian giao dich bi huy',
              `fail_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thoi gian giao dich that bai',
              `payment_method` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `payment_provider_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `payment_bank_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `payment_transaction` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `request_content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `response_content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              PRIMARY KEY (`id`) USING BTREE,
              INDEX `wallet_client_id`(`wallet_client_id`) USING BTREE,
              INDEX `wallet_merchant_id`(`wallet_merchant_id`) USING BTREE,
              CONSTRAINT `wallet_transaction_ibfk_1` FOREIGN KEY (`wallet_client_id`) REFERENCES `wallet_client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT `wallet_transaction_ibfk_2` FOREIGN KEY (`wallet_merchant_id`) REFERENCES `wallet_merchant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE = InnoDB AUTO_INCREMENT = 1303 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;
            DROP TABLE IF EXISTS `wallet_transaction_log`;
            CREATE TABLE `wallet_transaction_log`  (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `wallet_transaction_id` int(11) NULL DEFAULT NULL,
              `create_at` datetime(0) NULL DEFAULT NULL,
              `update_at` datetime(0) NULL DEFAULT NULL,
              `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `user_action` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              `content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1273 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

            SET FOREIGN_KEY_CHECKS = 1;";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190515_044109_wallet cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190515_044109_wallet cannot be reverted.\n";

        return false;
    }
    */
}
