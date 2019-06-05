<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `image`.
 */
class m190402_064332_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->comment('Store ID reference'),
            'reference' => $this->string(100)->comment('Reference key'),
            'reference_id' => $this->integer(11)->comment('Reference identity'),
            'base_path' => $this->string(255)->comment('basePath'),
            'name' => $this->string(255)->comment('name'),
            'full_path' => $this->string(255)->comment('tmp name'),
            'width' => $this->integer(11)->comment('saved width'),
            'height' => $this->integer(11)->comment('saved height'),
            'quality' => $this->integer(11)->comment('saved quality'),
            'size' => $this->integer(11)->comment('saved size (mb)'),
            'type' => $this->string(100)->comment('Image Type (jpg,png)'),
            'is_uploaded' => $this->smallInteger()->defaultValue(0)->comment('1 is form upload'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'uploaded_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'uploaded_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'uploaded_from_ip' => $this->string(20)->comment('Updated from ip address'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
    }
}
