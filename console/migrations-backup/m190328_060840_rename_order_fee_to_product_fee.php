<?php

use yii\db\Migration;

/**
 * Class m190328_060840_rename_order_fee_to_product_fee
 */
class m190328_060840_rename_order_fee_to_product_fee extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('order_fee', 'product_fee');
        $this->remakeForeignKey('product_fee');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('product_fee', 'order_fee');
        $this->remakeForeignKey('order_fee');
    }

    private function remakeForeignKey($name)
    {
        $sql = <<< SQL
SELECT
    kcu.constraint_name,
    kcu.column_name,
    kcu.referenced_table_name,
    kcu.referenced_column_name
FROM information_schema.referential_constraints AS rc
JOIN information_schema.key_column_usage AS kcu ON
    (
        kcu.constraint_catalog = rc.constraint_catalog OR
        (kcu.constraint_catalog IS NULL AND rc.constraint_catalog IS NULL)
    ) AND
    kcu.constraint_schema = rc.constraint_schema AND
    kcu.constraint_name = rc.constraint_name
WHERE rc.constraint_schema = database() AND kcu.table_schema = database()
AND rc.table_name = :tableName AND kcu.table_name = :tableName
SQL;
        $foreignKeys = $this->db->createCommand($sql, [':tableName' => $name])->queryAll();
        foreach ($foreignKeys as $foreignKey) {
            $this->dropForeignKey($foreignKey['constraint_name'],$name);
//            $this->addForeignKey(
//                "fk-$name-{$foreignKey['column_name']}",
//                $name,
//                $foreignKey['column_name'],
//                $foreignKey['referenced_table_name'],
//                $foreignKey['referenced_column_name']
//            );
        }
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190328_060840_rename_order_fee_to_product_fee cannot be reverted.\n";

        return false;
    }
    */
}
