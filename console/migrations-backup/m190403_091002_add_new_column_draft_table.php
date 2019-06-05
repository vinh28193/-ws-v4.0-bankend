<?php

use yii\db\Migration;

/**
 * Class m190403_091002_add_new_column_draft_table
 */
class m190403_091002_add_new_column_draft_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_missing_tracking','item_name','varchar(255) null COMMENT \'tên sản phẩm trả về từ boxme\'');
        $this->addColumn('draft_missing_tracking','warehouse_tag_boxme','varchar(255) null COMMENT \'wtag của boxme\'');
        $this->addColumn('draft_missing_tracking','note_boxme','varchar(255) null COMMENT \'note của boxme\'');
        $this->addColumn('draft_missing_tracking','image','varchar(255) null COMMENT \'các hình ảnh cách nhau bởi dấu phẩy\'');

        $this->addColumn('draft_wasting_tracking','item_name','varchar(255) null COMMENT \'tên sản phẩm trả về từ boxme\'');
        $this->addColumn('draft_wasting_tracking','warehouse_tag_boxme','varchar(255) null COMMENT \'wtag của boxme\'');
        $this->addColumn('draft_wasting_tracking','note_boxme','varchar(255) null COMMENT \'note của boxme\'');
        $this->addColumn('draft_wasting_tracking','image','varchar(255) null COMMENT \'các hình ảnh cách nhau bởi dấu phẩy\'');

        $this->addColumn('draft_package_item','item_name','varchar(255) null COMMENT \'tên sản phẩm trả về từ boxme\'');
        $this->addColumn('draft_package_item','warehouse_tag_boxme','varchar(255) null COMMENT \'wtag của boxme\'');
        $this->addColumn('draft_package_item','note_boxme','varchar(255) null COMMENT \'note của boxme\'');
        $this->addColumn('draft_package_item','image','varchar(255) null COMMENT \'các hình ảnh cách nhau bởi dấu phẩy\'');

        $this->addColumn('draft_boxme_tracking','item_name','varchar(255) null COMMENT \'tên sản phẩm trả về từ boxme\'');
        $this->addColumn('draft_boxme_tracking','warehouse_tag_boxme','varchar(255) null COMMENT \'wtag của boxme\'');
        $this->addColumn('draft_boxme_tracking','note_boxme','varchar(255) null COMMENT \'note của boxme\'');
        $this->addColumn('draft_boxme_tracking','image','varchar(255) null COMMENT \'các hình ảnh cách nhau bởi dấu phẩy\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190403_091002_add_new_column_draft_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190403_091002_add_new_column_draft_table cannot be reverted.\n";

        return false;
    }
    */
}
