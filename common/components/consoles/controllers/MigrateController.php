<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 11:34
 */

namespace common\components\consoles\controllers;

use Yii;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class MigrateController extends \yii\console\controllers\MigrateController
{

    /**
     * @var bool
     */
    public $useTablePrefix = false;

    /**
     * @var array
     */
    public $generatorTemplateFiles = [
        'create_table' => '@common/views/createTableMigration.php',
        'drop_table' => '@common/views/dropTableMigration.php',
        'add_column' => '@common/views/addColumnMigration.php',
        'drop_column' => '@common/views/dropColumnMigration.php',
        'create_junction' => '@common/views/createTableMigration.php',
    ];

    /**
     * @inheritdoc
     * @param $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function generateMigrationSourceCode($params)
    {
        $parsedFields = $this->parseFields();
        $fields = $parsedFields['fields'];
        $foreignKeys = $parsedFields['foreignKeys'];

        $name = $params['name'];

        $templateFile = $this->templateFile;
        $table = null;
        if (preg_match('/^create_junction(?:_table_for_|_for_|_)(.+)_and_(.+)_tables?$/', $name, $matches)) {
            $templateFile = $this->generatorTemplateFiles['create_junction'];
            $firstTable = $matches[1];
            $secondTable = $matches[2];

            $fields = array_merge(
                [
                    [
                        'property' => $firstTable . '_id',
                        'decorators' => 'integer()',
                    ],
                    [
                        'property' => $secondTable . '_id',
                        'decorators' => 'integer()',
                    ],
                ],
                $fields,
                [
                    [
                        'property' => 'PRIMARY KEY(' .
                            $firstTable . '_id, ' .
                            $secondTable . '_id)',
                    ],
                ]
            );

            $foreignKeys[$firstTable . '_id']['table'] = $firstTable;
            $foreignKeys[$secondTable . '_id']['table'] = $secondTable;
            $foreignKeys[$firstTable . '_id']['column'] = null;
            $foreignKeys[$secondTable . '_id']['column'] = null;
            $table = $firstTable . '_' . $secondTable;
        } elseif (preg_match('/^add_(.+)_columns?_to_(.+)_table$/', $name, $matches)) {
            $templateFile = $this->generatorTemplateFiles['add_column'];
            $table = $matches[2];
        } elseif (preg_match('/^drop_(.+)_columns?_from_(.+)_table$/', $name, $matches)) {
            $templateFile = $this->generatorTemplateFiles['drop_column'];
            $table = $matches[2];
        } elseif (preg_match('/^create_(.+)_table$/', $name, $matches)) {
            $this->addDefaultPrimaryKey($fields);
            $this->addGuestFields($fields);
            $templateFile = $this->generatorTemplateFiles['create_table'];
            $table = $matches[1];
        } elseif (preg_match('/^drop_(.+)_table$/', $name, $matches)) {
            $this->addDefaultPrimaryKey($fields);
            $templateFile = $this->generatorTemplateFiles['drop_table'];
            $table = $matches[1];
        }

        foreach ($foreignKeys as $column => $foreignKey) {
            $relatedColumn = $foreignKey['column'];
            $relatedTable = $foreignKey['table'];
            // Since 2.0.11 if related column name is not specified,
            // we're trying to get it from table schema
            // @see https://github.com/yiisoft/yii2/issues/12748
            if ($relatedColumn === null) {
                $relatedColumn = 'id';
                try {
                    $this->db = Instance::ensure($this->db, Connection::className());
                    $relatedTableSchema = $this->db->getTableSchema($relatedTable);
                    if ($relatedTableSchema !== null) {
                        $primaryKeyCount = count($relatedTableSchema->primaryKey);
                        if ($primaryKeyCount === 1) {
                            $relatedColumn = $relatedTableSchema->primaryKey[0];
                        } elseif ($primaryKeyCount > 1) {
                            $this->stdout("Related table for field \"{$column}\" exists, but primary key is composite. Default name \"id\" will be used for related field\n", Console::FG_YELLOW);
                        } elseif ($primaryKeyCount === 0) {
                            $this->stdout("Related table for field \"{$column}\" exists, but does not have a primary key. Default name \"id\" will be used for related field.\n", Console::FG_YELLOW);
                        }
                    }
                } catch (\ReflectionException $e) {
                    $this->stdout("Cannot initialize database component to try reading referenced table schema for field \"{$column}\". Default name \"id\" will be used for related field.\n", Console::FG_YELLOW);
                }
            }
            $foreignKeys[$column] = [
                'idx' => $this->generateTableName("idx-$table-$column"),
                'fk' => $this->generateTableName("fk-$table-$column"),
                'relatedTable' => $this->generateTableName($relatedTable),
                'relatedColumn' => $relatedColumn,
            ];


        }
         // Add column storeReferenceKey to migrate
        $storeManager = Yii::$app->storeManager;
        $storeReferenceKey = $storeManager->storeReferenceKey;
        /** @var  $class  \yii\db\ActiveRecord */
        $class = $storeManager->storeClass;
        $relatedTable = $class::tableName();
        $relatedTable = str_replace(['{{','%','}}'],'',$relatedTable);
        if(!isset($foreignKeys[$storeReferenceKey])){
            $foreignKeys[$storeManager->storeReferenceKey] = [
                'idx' => $this->generateTableName("idx-$table-$storeReferenceKey"),
                'fk' => $this->generateTableName("fk-$table-$storeReferenceKey"),
                'relatedTable' => $this->generateTableName($relatedTable),
                'relatedColumn' => $storeReferenceKey,
            ];
        }

        return $this->renderFile(Yii::getAlias($templateFile), array_merge($params, [
            'table' => $this->generateTableName($table),
            'fields' => $fields,
            'foreignKeys' => $foreignKeys,
            'tableComment' => $this->comment,
        ]));
    }

    /**
     * Adds default primary key to fields list if there's no primary key specified.
     * @param array $fields parsed fields
     * @since 2.0.7
     */
    protected function addDefaultPrimaryKey(&$fields)
    {
        foreach ($fields as $field) {
            if (false !== strripos($field['decorators'], 'primarykey()')) {
                return;
            }
        }
        array_unshift($fields,
            ['property' => 'id', 'decorators' => 'primaryKey()->comment(\'ID\')'],
            ['property' => Yii::$app->storeManager->storeReferenceKey, 'decorators' => 'integer(11)->notNull()->comment(\'Store ID reference\')']

        );
    }

    protected function addGuestFields(&$fields){
        $guestFields = [
            'status' => 'smallInteger()->defaultValue(1)->comment(\'Status (1:Active;2:Inactive)\')',
            'created_by' => 'integer(11)->defaultValue(null)->comment(\'Created by\')',
            'created_at' => 'integer(11)->defaultValue(null)->comment(\'Created at (timestamp)\')',
            'updated_by' => 'integer(11)->defaultValue(null)->comment(\'Updated by\')',
            'updated_at' => 'integer(11)->defaultValue(null)->comment(\'Updated at (timestamp)\')',
        ];
        $checkFields = $fields;
        $checkFields = array_keys(ArrayHelper::index($checkFields,'property'));
        $exist = [];
        $keys = array_keys($guestFields);
        foreach ($checkFields as $property) {
           if(in_array($property,$keys)){
               $exist[] = $property;
           }
        }
        foreach ($guestFields as $property => $decorators){
            $fields[] = ['property' => $property, 'decorators' => $decorators];
        }
    }
}