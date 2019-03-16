<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 09:29
 */

namespace common\fixtures;


use yii\test\ActiveFixture;

class StoreFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\Store';
    public $depends = [
        'common\fixtures\SystemCountryFixture',
        'common\fixtures\SystemCurrencyFixture'
    ];
    public $dataFile = '@common/fixtures/data/data_fixed/store.php';

    /*
     // weshop-v4.back-end.local.vn
     //[1, 'vi', 'Weshop Dev VN', ' Viet Nam', '18 Tam Trinh', 'weshop-v4.back-end.local.vn', 'VND', 1, 'dev']
    */
    public function createDefaultData()
    {
        return [
            'id' => 1,
            'country_id' => 'vi',
            'locale' => 'Weshop Dev VN',
            'name' => 'Viet Nam',
            'country_name' => 'Viet Nam',
            'address' => '18 Tam Trinh',
            'url' => 'weshop-v4.back-end.local.vn',
            'currency' => 'VND',
            'currency_id' => 1,
            'status' => 1,
            'env' => 'dev',
        ];
    }


    /**
     * @inheritdoc
     */
    public function load()
    {
        $this->data = [];
        $table = $this->getTableSchema();
        $default = $this->createDefaultData();
        $primaryKeys = $this->db->schema->insert($table->fullName, $default);
        $this->data['default'] = array_merge($default, $primaryKeys);
        try {
            foreach ($this->getData() as $alias => $row) {
                $id = isset($row['id']) ? $row['id'] : null;
                if ($id !== null) {
                    $id += 1;
                    $row['id'] = $id;
                }
                $primaryKeys = $this->db->schema->insert($table->fullName, $row);
                $this->data[$alias] = array_merge($row, $primaryKeys);
            }
        } catch (\Exception $e) {
            echo "Error importing load data * !\n";
            echo $e->getMessage() . ' at ' . $e->getLine() . ' in ' . $e->getFile() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
        }
    }

}
