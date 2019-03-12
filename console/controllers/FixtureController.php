<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-12
 * Time: 11:53
 */

namespace console\controllers;

use Yii;
use yii\db\Connection;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\FileHelper;

class FixtureController extends \yii\faker\FixtureController
{
    public $db = 'db';

    public $tableName;


    public function options($actionID)
    {
        return array_merge(parent::options($actionID),
            $actionID === 'dump-table' ? ['db', 'tableName'] : []
        );
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id === 'dump-table') {
                if (!Yii::$app->has($this->db)) {
                    $this->stdout("call unknown component `{$this->db}`\n", Console::FG_RED);
                    return false;
                } elseif (!($db = Yii::$app->get($this->db)) instanceof Connection) {
                    $this->stdout("component `{$this->db}` dose not instance of yii\db\Connection. \n", Console::FG_RED);
                    return false;
                } else {
                    $this->db = $db;
                }
                if ($this->tableName === null || $this->tableName === '') {
                    $this->stdout("parameter --tableName can not be blank.\n", Console::FG_RED);
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function actionDumpTable()
    {
        if(!$this->confirm("Generate above fixture for table `{$this->tableName}` ?. \n")){
            return static::EXIT_CODE_NORMAL;
        }

        $query = new Query();
        $query->from(['tb' => $this->tableName]);
        $exports = $query->all($this->db);
        $fixtures = [];
        foreach ($exports as $i => $export) {
            $fixtures[$this->tableName . $i] = $export;
        }
        $content = $this->exportFixtures($fixtures);

        $fixtureDataPath = Yii::getAlias($this->fixtureDataPath);
        // data file full path
        $dataFile = $fixtureDataPath . '/'. $this->tableName . '.php';

        // data file directory, create if it doesn't exist
        $dataFileDir = dirname($dataFile);
        if (!file_exists($dataFileDir)) {
            FileHelper::createDirectory($dataFileDir);
        }
        file_put_contents($dataFile, $content);
        $this->stdout("exported data to $dataFile \n",Console::FG_GREEN);
    }
}