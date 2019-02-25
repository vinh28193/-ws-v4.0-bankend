<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 13:34
 */

namespace common\components\db\mysql;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class QueryBuilder extends \yii\db\mysql\QueryBuilder
{

    /**
     * @var \common\components\StoreManager | string
     */
    public $storeManager = 'storeManager';

    public function init()
    {
        parent::init();
        $this->storeManager = Yii::$app->get($this->storeManager);
    }

    /**
     * @param $table
     * @return bool
     */
    private function isExcludeTable($table)
    {
        $excludeTables = $this->storeManager->getExcludeTables();
        if (in_array($table, $excludeTables)) {
            return true;
        }
        $tableName = preg_replace_callback(
            '/\\{\\{(%?[\w\-\. ]+%?)\\}\\}/',
            function ($matches) {
                return str_replace('%', $this->db->tablePrefix, $matches[1]);
            },
            $table
        );
        return in_array($tableName, $excludeTables);
    }

    /**
     * @inheritdoc
     */
    public function build($query, $params = [])
    {
        $from = [];
        if (!empty($query->from)) {
            if (ArrayHelper::keyExists('c', $query->from)) {
                $from[] = 'c';
            } else {
                $from = $query->from;
            }
        } elseif ($query instanceof ActiveQuery) {
            $modelClass = $query->modelClass;
            $from[] = $modelClass::tableName();
        }

        $filteredFrom = [];
        foreach ($from as $key => $f) {
            if (!$f instanceof Query && !$this->isExcludeTable($f)) {
                $filteredFrom[$key] = $f;
            }
        }

        $tables = preg_grep('/^' . implode('|', $this->storeManager->getExcludeTables()) . '$/', $filteredFrom, PREG_GREP_INVERT);
        foreach ($tables as $key => $tableName) {
            if (is_string($key)) {
                $tableName = $key;
            }

            $query->andWhere([
                $tableName . '.' . $this->storeManager->getStoreReferenceKey() => $this->storeManager->getId(),
            ]);
        }
        return parent::build($query, $params);
    }

    /**
     * @inheritdoc
     * @param array $joins
     * @param array $params
     * @return string
     * @throws \yii\db\Exception
     */
    public function buildJoin($joins, &$params)
    {
        foreach ((array)$joins as $i => $join) {
            if (is_string($join[1])) {
                if (in_array($join[1], $this->storeManager->excludeTables)) break;

                if (isset($join[2])) {
                    $join[2] = ['AND', $join[1] . '.' . $this->storeManager->getStoreReferenceKey() . ' = ' . $this->storeManager->getId(), $join[2]];
                } else {
                    $join[2] = $join[1] . '.' . $this->storeManager->getStoreReferenceKey() . ' = ' . $this->storeManager->getId();
                }
                $joins[$i] = $join;
            }
        }

        return parent::buildJoin($joins, $params);
    }

    public function insert($table, $columns, &$params)
    {
        if (!$this->isExcludeTable($table)) {
            $columns[$this->storeManager->getStoreReferenceKey()] = $this->storeManager->getId();
        }
        return parent::insert($table, $columns, $params);
    }

    /**
     * @inheritdoc
     * @param string $table
     * @param array $columns
     * @param array|string $condition
     * @param array $params
     * @return string
     */
    public function update($table, $columns, $condition, &$params)
    {
        if (!$this->isExcludeTable($table)) {
            $condition = ['AND', $condition, [$this->storeManager->getStoreReferenceKey() => $this->storeManager->getId()]];
            $columns[$this->storeManager->getStoreReferenceKey()] = $this->storeManager->getId();
        }
        return parent::update($table, $columns, $condition, $params);
    }

    /**
     * @inheritdoc
     * @param string $table
     * @param array|string $condition
     * @param array $params
     * @return string
     */
    public function delete($table, $condition, &$params)
    {
        if (!$this->isExcludeTable($table)) {
            $condition = ['AND', $condition, [$this->storeManager->getStoreReferenceKey() => $this->storeManager->getId()]];
        }
        return parent::delete($table, $condition, $params);
    }
}