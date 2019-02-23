<?php

use yii\db\Migration;

class m160309_070856_create_post extends Migration
{
    public function up()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $sql="INSERT INTO `post` (`id`, `title`, `text`, `status`, `created_at`, `updated_at`) VALUES (1,'Weshop','Test Post',0,'2017-09-16 03:09:12',CURRENT_TIMESTAMP),(2,'Hoang Phuc','Weshop Globla',0,'2017-09-16 03:09:12',CURRENT_TIMESTAMP);";
        Yii::$app->db->createCommand($sql)->execute();


        /*
         INSERT INTO `post` (`id`, `title`, `text`, `status`, `created_at`, `updated_at`) VALUES
            (1,'Weshop','Test Post',0,'2017-09-16 03:09:12',CURRENT_TIMESTAMP),
            (2,'Hoang Phuc','Weshop Globla',0,'2017-09-16 03:09:12',CURRENT_TIMESTAMP);
         */
    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
