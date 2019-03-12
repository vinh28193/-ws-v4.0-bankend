##---------- Config Additional Fee --------------
  
  -- Step 1: create fee config
  
        - create migrate run : 
        
        ----> php yii migrate\create insert_test_fee_into_store_additional_fee_table
            Yii Migration Tool (based on Yii v2.0.17-dev)
            
            Create new migration 'C:\workspaces\ejector\weshop-v4.0-api/console/migrations\m190226_034910_insert_test_fee_into_store_additional_fee_table.php'? (yes|no) [no]:
        ----> yes
            New migration created successfully.
        
        - Fix file `m190226_034910_insert_test_fee_into_store_additional_fee_table.php`
           
           /**
            * Class m190226_034910_insert_test_fee_into_store_additional_fee_table
            */
           class m190226_034910_insert_test_fee_into_store_additional_fee_table extends Migration
           {
               /**
                * {@inheritdoc}
                */
               public function safeUp()
               {
           
                   $this->insert('store_additional_fee', [
                       'name' => 'test_fee',
                       'currency' => 'VND',
                       'description' => 'dev fee',
                       'is_convert' => 1,
                       'is_read_only' => 0
                   ]);
               }
           
               /**
                * {@inheritdoc}
                */
               public function safeDown()
               {
                   $this->delete('store_additional_fee', ['name' => 'test_fee']);
               }
           
               /*
               // Use up()/down() to run migration code without a transaction.
               public function up()
               {
           
               }
           
               public function down()
               {
                   echo "m190226_034910_insert_test_fee_into_store_additional_fee_table cannot be reverted.\n";
           
                   return false;
               }
               */
           }
           
  -- Create Condition
        
            namespace .....
            class TestCondition extends \common\components\conditions\BaseCondition
            {
            
                public $name = 'TestCondition';
                
                public function execute($value, $additionalFee, $storeAdditionalFee)
                {
                    // Or re turn your condition
                    return $value;
                }
            }
            
  -- Apply condition for test_fee (2 ways)
                -- update migrate m190226_034910_insert_test_fee_into_store_additional_fee_table
                        /**
                        * {@inheritdoc}
                        */
                       public function safeUp()
                       {
                           $condition = new TestCondition();
                           $this->insert('store_additional_fee', [
                               'name' => 'test_fee',
                               'currency' => 'VND',
                               'description' => 'dev fee',
                               'is_convert' => 1,
                               'is_read_only' => 0,
                               'condition_name' => $condition->name,
                               'condition_data' => serialize($condition)
                           ]);
                       }
                -- using common/components/conditions/ConditionTrait:: setCondition 
                    
                    $storeAdditinalFee = StoreAdditianlFee::findOne(['name' => 'test_fee']);
                    $condition = new TestCondition();
                    $storeAdditinalFee->setCondition($condition);
                
        
  -- Use :
            $order = new Order();
            $order->setAdditionalFee(['test_fee' => 100]);
            var_dump($order->getAdditionalFee('test_fee'));
            
  -- Note Register Total Test Fee for Order object
            -- create new attribute in Order name "total_test_fee_local"
            -- total_test_fee_local = sum all test_fee (using local_amount)