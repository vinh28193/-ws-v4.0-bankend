<?php

use yii\db\Migration;


/**
 * Class m190613_111011_weshop_v4_datatabe_bootstrap
 */
class m190613_111011_weshop_v4_datatabe_bootstrap extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        $this->truncateTable('{{%store}}');
        $this->alterColumn('{{%store}}', 'name', $this->string(255)->notNull()->after('country_name'));
        $this->alterColumn('{{%store}}', 'locale', $this->string(5)->notNull()->after('url'));
        $this->addColumn('{{%store}}', 'symbol', $this->string(5)->notNull()->after('currency'));
        $this->addColumn('{{%store}}', 'country_code', $this->string(5)->notNull()->after('country_id'));
        $this->batchInsert('{{%store}}',
            ['id', 'country_id', 'country_code', 'country_name', 'name', 'address', 'url', 'locale', 'currency', 'symbol', 'status', 'env', 'version'],
            [
                [1, 1, 'VN', 'Việt Nam', 'Weshop Viet Nam', '18 Tam Trinh, Hà Nội', 'weshop.com.vn', 'vi', 'VND', '₫', 1, 'prod', '4.0'],
                [2, 2, 'ID', 'Indonesia', 'Weshop Indonesia', '18 Tam Trinh, Hà Nội', 'weshop.co.id', 'id', 'IDR', 'Rp', 1, 'prod', '4.0'],
            ]
        );

        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");

        $this->addColumn('{{%store}}', 'name', $this->string(255));
        $this->alterColumn('{{%store}}', 'locale', $this->string(5)->notNull()->after('country_id'));
        $this->dropColumn('{{%store}}', 'symbol');
        $this->dropColumn('{{%store}}', 'country_code');

        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190613_111011_weshop_v4_datatabe_bootstrap cannot be reverted.\n";

        return false;
    }
    */
}
