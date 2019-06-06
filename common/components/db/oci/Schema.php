<?php

/**
 * Oracle db Schema
 * @author vinhvv@preacesoft.net
 * @copyright weshop.com.vn
 */

namespace common\components\db\oci;

use yii\db\Expression;
use yii\db\TableSchema;
use yii\db\ColumnSchema;

/**
 * Class Schema
 * @package common\components\db\oci
 */
class Schema extends \yii\db\oci\Schema
{


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->defaultSchema === null) {
            $this->defaultSchema = $this->ensureUpper($this->db->username);
        }
    }


    /**
     * @inheritdoc
     */
    public function quoteSimpleTableName($name)
    {
        return strpos($name, '"') !== false ? $name : '"' . $this->ensureUpper($name) . '"';
    }

    /**
     * @inheritdoc
     */
    public function quoteSimpleColumnName($name)
    {
        return strpos($name, '"') !== false || $name === '*' ? $name : '"' . $this->ensureUpper($name) . '"';
    }


    /**
     * @inheritdoc
     */
    protected function resolveTableNames($table, $name)
    {
        $parts = explode('.', str_replace('"', '', $name));
        if (isset($parts[1])) {
            $table->schemaName = $parts[0];
            $table->name = $this->ensureUpper($parts[1]); //ced (todo: take care about config pdo attr)
        } else {
            $table->schemaName = $this->defaultSchema;
            $table->name = $this->ensureUpper($name); //ced (todo: take care about config pdo attr)
        }

        $table->fullName = $table->schemaName !== $this->defaultSchema ? $table->schemaName . '.' . $table->name : $table->name;
    }

    /**
     * @inheritdoc
     */
    protected function createColumn($column)
    {
        $c = $this->createColumnSchema();
        // solve case troubles with oracle
        //(table et column names are in uppercase in oracle; attributes are in lowercase in yii)
        $c->name = $this->ensureLower($column['COLUMN_NAME']);
//        if ($this->db->slavePdo->getAttribute(\PDO::ATTR_CASE) === \PDO::CASE_LOWER) {
//            $c->name = $this->ensureLower($column['COLUMN_NAME']);
//        }
//        else {
//            $c->name = $column['COLUMN_NAME'];
//        }
        $c->allowNull = $column['NULLABLE'] === 'Y';
        $c->isPrimaryKey = isset($column['KEY']) && strpos($column['KEY'], 'P') !== false;
        $c->comment = $column['COLUMN_COMMENT'] === null ? '' : $column['COLUMN_COMMENT'];

        $this->extractColumnType($c, $column['DATA_TYPE'], $column['DATA_PRECISION'], $column['DATA_SCALE'], $column['DATA_LENGTH']);
        $this->extractColumnSize($c, $column['DATA_TYPE'], $column['DATA_PRECISION'], $column['DATA_SCALE'], $column['DATA_LENGTH']);

        $c->phpType = $this->getColumnPhpType($c);

        if (!$c->isPrimaryKey) {
            if (stripos($column['DATA_DEFAULT'], 'timestamp') !== false) {
                $c->defaultValue = null;
            } else {
                $defaultValue = $column['DATA_DEFAULT'];
                if ($c->type === 'timestamp' && $defaultValue === 'CURRENT_TIMESTAMP') {
                    $c->defaultValue = new Expression('CURRENT_TIMESTAMP');
                } else {
                    if ($defaultValue !== null) {
                        if (($len = strlen($defaultValue)) > 2 && $defaultValue[0] === "'"
                            && $defaultValue[$len - 1] === "'"
                        ) {
                            $defaultValue = substr($column['DATA_DEFAULT'], 1, -1);
                        } else {
                            $defaultValue = trim($defaultValue);
                        }
                    }
                    $c->defaultValue = $c->phpTypecast($defaultValue);
                }
            }
        }

        return $c;
    }

    /**
     * Finds constraints and fills them into TableSchema object passed
     * @param TableSchema $table
     */
    protected function findConstraints($table)
    {
        $sql = <<<SQL
SELECT D.CONSTRAINT_NAME, D.CONSTRAINT_TYPE, C.COLUMN_NAME, C.POSITION, D.R_CONSTRAINT_NAME,
        E.TABLE_NAME AS TABLE_REF, F.COLUMN_NAME AS COLUMN_REF,
        C.TABLE_NAME
FROM ALL_CONS_COLUMNS C
INNER JOIN ALL_CONSTRAINTS D ON D.OWNER = C.OWNER AND D.CONSTRAINT_NAME = C.CONSTRAINT_NAME
LEFT JOIN ALL_CONSTRAINTS E ON E.OWNER = D.R_OWNER AND E.CONSTRAINT_NAME = D.R_CONSTRAINT_NAME
LEFT JOIN ALL_CONS_COLUMNS F ON F.OWNER = E.OWNER AND F.CONSTRAINT_NAME = E.CONSTRAINT_NAME AND F.POSITION = C.POSITION
WHERE C.OWNER = :schemaName
   AND C.TABLE_NAME = :tableName
ORDER BY D.CONSTRAINT_NAME, C.POSITION
SQL;
        $command = $this->db->createCommand($sql, [
            ':tableName' => $table->name,
            ':schemaName' => $table->schemaName,
        ]);
        $constraints = [];
        foreach ($command->queryAll() as $row) {
            //Fixed Oracle DB `PDO::ATTR_CASE = PDO::CASE_UPPER` did not work
            if ($this->db->slavePdo->getAttribute(Pdo::ATTR_CASE) === Pdo::CASE_LOWER) {
                $row = array_change_key_case($row, CASE_UPPER);
            }
            if ($row['CONSTRAINT_TYPE'] !== 'R') {
                // this condition is not checked in SQL WHERE because of an Oracle Bug:
                // see https://github.com/yiisoft/yii2/pull/8844
                continue;
            }
            $name = $row['CONSTRAINT_NAME'];
            if (!isset($constraints[$name])) {
                $constraints[$name] = [
                    'tableName' => $row["TABLE_REF"],
                    'columns' => [],
                ];
            }
            $constraints[$name]['columns'][$row["COLUMN_NAME"]] = $row["COLUMN_REF"];
        }
        foreach ($constraints as $constraint) {
            $table->foreignKeys[] = array_merge([$constraint['tableName']], $constraint['columns']);
        }
    }

    /**
     * @inheritdoc
     */
    protected function findSchemaNames()
    {
        $sql = <<<SQL
SELECT username
  FROM dba_users u
 WHERE EXISTS (
    SELECT 1
      FROM dba_objects o
     WHERE o.owner = u.username )
   AND default_tablespace not in ('SYSTEM','SYSAUX')
SQL;
        return $this->db->createCommand($sql)->queryColumn();
    }

    /**
     * @inheritdoc
     */
    protected function findTableNames($schema = '')
    {
        if ($schema === '') {
            $sql = <<<SQL
SELECT table_name FROM user_tables
UNION ALL
SELECT view_name AS table_name FROM user_views
UNION ALL
SELECT mview_name AS table_name FROM user_mviews
ORDER BY table_name
SQL;
            $command = $this->db->createCommand($sql);
        } else {
            $sql = <<<SQL
SELECT object_name AS table_name
FROM all_objects
WHERE object_type IN ('TABLE', 'VIEW', 'MATERIALIZED VIEW') AND owner=:schema
ORDER BY object_name
SQL;
            $command = $this->db->createCommand($sql, [':schema' => $schema]);
        }

        $rows = $command->queryAll();
        $names = [];
        foreach ($rows as $row) {
            if ($this->db->slavePdo->getAttribute(\PDO::ATTR_CASE) === \PDO::CASE_LOWER) {
                $row = array_change_key_case($row, CASE_UPPER);
            }
            $names[] = $row['TABLE_NAME'];
        }
        return $names;
    }

    /**
     * @inheritdoc
     */
    public function findUniqueIndexes($table)
    {
        $query = <<<SQL
SELECT dic.INDEX_NAME, dic.COLUMN_NAME
FROM ALL_INDEXES di
INNER JOIN ALL_IND_COLUMNS dic ON di.TABLE_NAME = dic.TABLE_NAME AND di.INDEX_NAME = dic.INDEX_NAME
WHERE di.UNIQUENESS = 'UNIQUE'
AND dic.TABLE_OWNER = :schemaName
AND dic.TABLE_NAME = :tableName
ORDER BY dic.TABLE_NAME, dic.INDEX_NAME, dic.COLUMN_POSITION
SQL;
        $result = [];
        $command = $this->db->createCommand($query, [
            ':tableName' => $table->name,
            ':schemaName' => $table->schemaName,
        ]);
        foreach ($command->queryAll() as $row) {
            $result[$row['INDEX_NAME']][] = $row['COLUMN_NAME'];
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function extractColumnType($column, $dbType, $precision, $scale, $length)
    {
        $column->dbType = $dbType;

        if (strpos($dbType, 'FLOAT') !== false || strpos($dbType, 'DOUBLE') !== false) {
            $column->type = 'double';
            //} elseif ($dbType == 'NUMBER' || strpos($dbType, 'INTEGER') !== false) {
            //    if ($scale !== null && $scale > 0) {
            // error type de column for some real values...
        } elseif (strpos($dbType, 'NUMBER') !== false) {
            if ($scale === null || $scale > 0) {
                $column->type = 'decimal';
            } else {
                $column->type = 'integer';
            }
        } elseif (strpos($dbType, 'BLOB') !== false) {
            $column->type = 'binary';
        } elseif (strpos($dbType, 'CLOB') !== false) {
            $column->type = 'text';
        } elseif (strpos($dbType, 'TIMESTAMP') !== false) {
            $column->type = 'timestamp';
        } else {
            $column->type = 'string';
        }
    }

    /**
     * @inheritdoc
     */
    protected function extractColumnSize($column, $dbType, $precision, $scale, $length)
    {
        $column->size = trim($length) == '' ? null : (int)$length;
        $column->precision = trim($precision) == '' ? null : (int)$precision;
        $column->scale = trim($scale) == '' ? null : (int)$scale;
    }

    /**
     * @inheritdoc
     */
    public function insert($table, $columns)
    {
        $params = [];
        $returnParams = [];
        $sql = $this->db->getQueryBuilder()->insert($table, $columns, $params);
        $tableSchema = $this->getTableSchema($table);
        $returnColumns = $tableSchema->primaryKey;
        if (!empty($returnColumns)) {
            $columnSchemas = $tableSchema->columns;
            $returning = [];
            foreach ((array)$returnColumns as $name) {
                $phName = QueryBuilder::PARAM_PREFIX . (count($params) + count($returnParams));
                $returnParams[$phName] = [
                    'column' => $name,
                    'value' => null,
                ];
                if (!isset($columnSchemas[$name]) || $columnSchemas[$name]->phpType !== 'integer') {
                    $returnParams[$phName]['dataType'] = Pdo::PARAM_STR;
                } else {
                    $returnParams[$phName]['dataType'] = Pdo::PARAM_INT;
                }
                $returnParams[$phName]['size'] = isset($columnSchemas[$name]) && isset($columnSchemas[$name]->size) ? $columnSchemas[$name]->size : -1;
                $returning[] = $this->quoteColumnName($name);
            }
            $sql .= ' RETURNING ' . implode(', ', $returning) . ' INTO ' . implode(', ', array_keys($returnParams));
        }

        $command = $this->db->createCommand($sql, $params);
        $command->prepare(false);

        foreach ($returnParams as $name => &$value) {
            $command->pdoStatement->bindParam($name, $value['value'], $value['dataType'], $value['size']);
        }

        if (!$command->execute()) {
            return false;
        }

        $result = [];
        foreach ($returnParams as $value) {
            $result[$value['column']] = $value['value'];
        }

        return $result;
    }

    /**
     * @param $string
     * @return string
     */
    public function ensureUpper($string)
    {
        // force uppercase (todo: check if we need to take care about config pdo attr)
//        if ($this->db->getSlavePdo()->getAttribute(Pdo::ATTR_CASE) !== Pdo::CASE_LOWER) {
//            return array_change_key_case($string, CASE_UPPER);
        return strtoupper($string);
//        }
//        return $string;

    }

    /**
     * @param $string
     * @return string
     */
    public function ensureLower($string)
    {
        // force lowercase (todo: check if we need to take care about config pdo attr)
//        if ($this->db->getSlavePdo()->getAttribute(Pdo::ATTR_CASE) !== Pdo::CASE_LOWER) {
//            return array_change_key_case($string, CASE_LOWER);
        return strtolower($string);
//        }
//        return $string;
    }

}