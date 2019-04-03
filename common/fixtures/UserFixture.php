<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\User';
    public $dataFile = '@common/fixtures/data/data_fixed/user.php';

    /**
     * @inheritdoc
     */
    /*
    public function load()
    {
        $this->data = [];
        $table = $this->getTableSchema();
        $default = $this->createDefaultData();
        $primaryKeys = $this->db->schema->insert($table->fullName, $default);
        $this->data['default'] = array_merge($default, $primaryKeys);

        $UserWS = $this->CreateUserWeshop();
        $primaryKeysWS = $this->db->schema->insert($table->fullName, $UserWS);
        $this->data['userWs'] = array_merge($UserWS, $primaryKeysWS);

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

    public function createDefaultData()
    {
        return [
            'id' => 1,
            'username' => 'weshopdev',
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('123456'),
            'password_reset_token' => null,
            'email' => 'weshop.dev@weshop.asia',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
            ];
    }

    public function CreateUserWeshop()
    {
        return [
            'id' => 13,
            'username' => 'weshop2019',
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('weshop@123'),
            'password_reset_token' => null,
            'email' => 'phuchc@weshop.asia',
            'status' => 1,  // 10
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
    */
}
