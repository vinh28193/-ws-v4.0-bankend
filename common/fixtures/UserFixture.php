<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\db\User';

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
        foreach ($this->getData() as $alias => $row) {
            $id = isset($row['id']) ? $row['id'] : null;
            if ($id !== null) {
                $id += 1;
                $row['id'] = $id;
            }
            $primaryKeys = $this->db->schema->insert($table->fullName, $row);
            $this->data[$alias] = array_merge($row, $primaryKeys);
        }
    }

    public function createDefaultData(){
        return [
            'id' => 1,
            'username' => 'weshopdev',
            'auth_key' => \Yii::$app->security->generateRandomString(40),
            'password_hash' => \Yii::$app->security->generatePasswordHash('123456'),
            'password_reset_token' => null,
            'email' => 'weshop.dev@weshop.asia',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}