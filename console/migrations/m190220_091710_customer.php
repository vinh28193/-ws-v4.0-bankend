    <?php

use yii\db\Migration;

/**
 * Class m190220_091710_customer
 */
class m190220_091710_customer extends Migration
{
    public $list = [
        [
            'column' => 'store_id',
            'table' => 'store',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('customer',[
            'id' => $this->primaryKey()->comment("ID"),
            'first_name' => $this->string(255)->comment(""),
            'last_name' => $this->string(255)->comment(""),
            'email' => $this->string(255)->comment(""),
            'phone' => $this->string(255)->comment(""),
            'user_name' => $this->string(255)->comment(""),
            'password' => $this->string(255)->comment(""),
            'gender' => $this->tinyInteger(4)->comment(""),
            'birthday' => $this->dateTime()->comment(""),
            'avatar' => $this->string(255)->comment(""),
            'link_verify' => $this->string(255)->comment(""),
            'email_verified' => $this->tinyInteger(4)->comment(""),
            'phone_verified' => $this->tinyInteger(4)->comment(""),
            'last_order_time' => $this->dateTime()->comment(""),
            'note_by_employee' => $this->text()->comment(""),
            'type_customer' => $this->integer(11)->comment(""),
            'access_token' => $this->string(255)->comment(""),
            'auth_client' => $this->string(255)->comment(""),
            'verify_token' => $this->string(255)->comment(""),
            'reset_password_token' => $this->string(255)->comment(""),
            'store_id' => $this->integer(11)->comment(""),
            'active_shipping' => $this->integer(11)->comment(""),
            'total_xu' => $this->decimal(18,1)->comment(""),
            'total_xu_start_date' => $this->bigInteger()->comment("Thoi điểm bắt đầu điểm tích lũy "),
            'total_xu_expired_date' => $this->bigInteger()->comment("Thoi điểm reset điểm tích lũy về 0"),
            'usable_xu' => $this->decimal(18,2)->comment("//tổng số xu có thể sử dụng (tgian 1 tháng)"),
            'usable_xu_start_date' => $this->bigInteger()->comment("Thoi điểm bắt đầu điểm tích lũy "),
            'usable_xu_expired_date' => $this->bigInteger()->comment("Thoi điểm reset điểm tích lũy về 0"),
            'last_use_xu' => $this->decimal(18,2)->comment(""),
            'last_use_time' => $this->bigInteger()->comment(""),
            'last_revenue_xu' => $this->decimal(18,2)->comment(""),
            'last_revenue_time' => $this->bigInteger()->comment(""),
            'verify_code' => $this->string(10)->comment(""),
            'verify_code_expired_at' => $this->bigInteger()->comment(""),
            'verify_code_count' => $this->integer(11)->comment(""),
            'verify_code_type' => $this->string(255)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'active' => $this->tinyInteger(4)->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ],$tableOptions);

        foreach ($this->list as $data){
            $this->createIndex('idx-customer-'.$data['column'],'customer',$data['column']);
            $this->addForeignKey('fk-customer-'.$data['column'], 'customer', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach ($this->list as $data){
            $this->dropIndex('idx-customer-'.$data['column'], 'customer');
            $this->dropForeignKey('fk-customer-'.$data['column'], 'customer');
        }
        $this->dropTable('customer');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_091710_customer cannot be reverted.\n";

        return false;
    }
    */
}
