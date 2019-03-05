<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-05
 * Time: 16:00
 */

namespace common\components;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;
use common\helpers\WeshopHelper;

class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * Converts an exception into an array.
     * @param \Exception|\Error $exception the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray($exception)
    {
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, Yii::t('yii', 'An internal server error occurred.'));
        }

        $message = $exception->getMessage();
        $data = [
            'name' => ($exception instanceof Exception || $exception instanceof ErrorException) ? $exception->getName() : 'Exception',
            'message' => $message,
            'code' => $exception->getCode(),
        ];

        if ($exception instanceof HttpException) {
            $data['status'] = $exception->statusCode;
        }
        $data['type'] = get_class($exception);
        if (!$exception instanceof UserException) {
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
            $data['stack-trace'] = explode("\n", $exception->getTraceAsString());
            if ($exception instanceof \yii\db\Exception) {
                $data['error-info'] = $exception->errorInfo;
            }
        }
        if (($prev = $exception->getPrevious()) !== null) {
            $data['previous'] = parent::convertExceptionToArray($prev);
        }

        return WeshopHelper::response(false,$message,YII_DEBUG ? $data : null);
    }
}