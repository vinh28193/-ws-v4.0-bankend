<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-02
 * Time: 14:45
 */

namespace common\components\log\driver;

use common\components\log\LoggingDriverInterface;
use common\components\log\LoggingHandleDriverException;
use common\components\log\LoggingHandleDriverFailInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\VarDumper;

class SaveFileLog extends \yii\base\BaseObject implements LoggingDriverInterface, LoggingHandleDriverFailInterface
{

    /**
     * @var string log file path or [path alias](guide:concept-aliases). If not set, it will use the "@runtime/logs/file.log" file.
     * The directory containing the log files will be automatically created if not existing.
     */
    public $logFilePath;

    /**
     * @var bool whether log files should be rotated when they reach a certain [[maxFileSize|maximum size]].
     * Log rotation is enabled by default. This property allows you to disable it, when you have configured
     * an external tools for log rotation on your server.
     * @since 2.0.3
     */
    public $enableRotation = true;
    /**
     * @var int maximum log file size, in kilo-bytes. Defaults to 10240, meaning 10MB.
     */
    public $maxFileSize = 5120; // in KB
    /**
     * @var int number of log files used for rotation. Defaults to 5.
     */
    public $maxLogFiles = 10;
    /**
     * @var int the permission to be set for newly created log files.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     */
    public $fileMode;
    /**
     * @var int the permission to be set for newly created directories.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner and group,
     * but read-only for other users.
     */
    public $dirMode = 0775;
    /**
     * @var bool Whether to rotate log files by copy and truncate in contrast to rotation by
     * renaming files. Defaults to `true` to be more compatible with log tailers and is windows
     * systems which do not play well with rename on open files. Rotation by renaming however is
     * a bit faster.
     *
     * The problem with windows systems where the [rename()](https://secure.php.net/manual/en/function.rename.php)
     * function does not work with files that are opened by some process is described in a
     * [comment by Martin Pelletier](https://secure.php.net/manual/en/function.rename.php#102274) in
     * the PHP documentation. By setting rotateByCopy to `true` you can work
     * around this problem.
     */
    public $rotateByCopy = true;


    private $_type;

    /**
     * @return string
     */
    public function getProvided()
    {
        if ($this->_type === null) {
            $this->_type = 'file';
        }
        return $this->_type;
    }

    public function init()
    {
        parent::init();

        if ($this->maxLogFiles < 1) {
            $this->maxLogFiles = 1;
        }
        if ($this->maxFileSize < 1) {
            $this->maxFileSize = 1;
        }
    }

    /**
     * @param LoggingDriverInterface|string $driver
     * @return $this|void
     * @throws \yii\base\Exception
     */
    public function resolve($driver)
    {
        if ($driver instanceof LoggingDriverInterface) {
            $this->_type = $driver->getProvided();
        } elseif (is_string($driver)) {
            $this->_type = $driver;
        }
    }

    /**
     * Rotates log files.
     */
    protected function rotateFiles()
    {
        $file = $this->logFilePath;
        for ($i = $this->maxLogFiles; $i >= 0; --$i) {
            // $i == 0 is the original log file
            $rotateFile = $file . ($i === 0 ? '' : '.' . $i);
            if (is_file($rotateFile)) {
                // suppress errors because it's possible multiple processes enter into this section
                if ($i === $this->maxLogFiles) {
                    @unlink($rotateFile);
                    continue;
                }
                $newFile = $this->logFilePath . '.' . ($i + 1);
                $this->rotateByCopy ? $this->rotateByCopy($rotateFile, $newFile) : $this->rotateByRename($rotateFile, $newFile);
                if ($i === 0) {
                    $this->clearLogFile($rotateFile);
                }
            }
        }
    }

    /***
     * Clear log file without closing any other process open handles
     * @param string $rotateFile
     */
    private function clearLogFile($rotateFile)
    {
        if ($filePointer = @fopen($rotateFile, 'a')) {
            @ftruncate($filePointer, 0);
            @fclose($filePointer);
        }
    }

    /***
     * Copy rotated file into new file
     * @param string $rotateFile
     * @param string $newFile
     */
    private function rotateByCopy($rotateFile, $newFile)
    {
        @copy($rotateFile, $newFile);
        if ($this->fileMode !== null) {
            @chmod($newFile, $this->fileMode);
        }
    }

    /**
     * Renames rotated file into new file
     * @param string $rotateFile
     * @param string $newFile
     */
    private function rotateByRename($rotateFile, $newFile)
    {
        @rename($rotateFile, $newFile);
    }

    /**
     * @param string $action
     * @param string $message
     * @param array $params
     * @return bool|mixed|void
     * @throws InvalidConfigException
     * @throws LoggingHandleDriverException
     * @throws \yii\base\Exception
     */
    public function pushData($action, $message, $params = [])
    {
        $logFile = "{$this->getProvided()}.log";
        if ($this->logFilePath === null) {
            $this->logFilePath = Yii::$app->getRuntimePath() . "/logs/$logFile";
        } else {
            $this->logFilePath .= "/$logFile";
            $this->logFilePath = Yii::getAlias($this->logFilePath);
        }
        $logPath = dirname($this->logFilePath);
        \yii\helpers\FileHelper::createDirectory($logPath, $this->dirMode, true);
        if (($fp = @fopen($this->logFilePath, 'a')) === false) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFilePath}");
        }
        $content = $this->resolveContent($action, $message, $params) . "\n";
        @flock($fp, LOCK_EX);
        if ($this->enableRotation) {
            // clear stat cache to ensure getting the real current file size and not a cached one
            // this may result in rotating twice when cached file size is used on subsequent calls
            clearstatcache();
        }
        if ($this->enableRotation && @filesize($this->logFilePath) > $this->maxFileSize * 1024) {
            @flock($fp, LOCK_UN);
            @fclose($fp);
            $this->rotateFiles();
            $writeResult = @file_put_contents($this->logFilePath, $content, FILE_APPEND | LOCK_EX);
            if ($writeResult === false) {
                $error = error_get_last();
                throw new LoggingHandleDriverException("Unable to write log through file!: {$error['message']}");
            }
            $textSize = strlen($content);
            if ($writeResult < $textSize) {
                throw new LoggingHandleDriverException("Unable to write whole log through file! Wrote $writeResult out of $textSize bytes.");
            }
        } else {
            $writeResult = @fwrite($fp, $content);
            if ($writeResult === false) {
                $error = error_get_last();
                throw new LoggingHandleDriverException("Unable to write log through file!: {$error['message']}");
            }
            $textSize = strlen($content);
            if ($writeResult < $textSize) {
                throw new LoggingHandleDriverException("Unable to write whole log through file! Wrote $writeResult out of $textSize bytes.");
            }
            @flock($fp, LOCK_UN);
            @fclose($fp);
        }
        if ($this->fileMode !== null) {
            @chmod($this->logFilePath, $this->fileMode);
        }
    }

    public function beginContent()
    {
        return <<<DOCBLOCK
TIME: {$this->getFormatter()->asDatetime('now')}
=============================BEGIN================================
DOCBLOCK;
    }

    public function endContent($text = "\n\n")
    {
        return <<<DOCBLOCK
=============================END==================================
{$text}
DOCBLOCK;

    }

    public function getFormatter()
    {
        return Yii::$app->getFormatter();
    }

    protected function resolveContent($action, $message, $params = [])
    {
        if(!empty($params)){
            $params = VarDumper::export($params);
        }
        $text = "[$action]\n[$message]\n$params";
        return <<<EOD
{$this->beginContent()}
$text
{$this->endContent("\n")}
EOD;

    }

    public function pullData($condition)
    {

    }
}