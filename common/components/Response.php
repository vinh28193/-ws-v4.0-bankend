<?php
/**
 * Created by PhpStorm.
 * User: ducquan
 * Date: 28/8/2015
 * Time: 11:04 AM
 */

namespace common\components;


/**
 * Class định nghĩa dữ liệu trả về từ Service
 * @package common\response
 */
class Response
{
  public $success = false;
  public $message = null;
  public $data = null;

  public function __construct($success = false, $message = null, $data = null)
  {
    $this->success = $success;
    $this->message = $message;
    $this->data = $data;
  }

  public static function json($success = false, $message = null, $data = null)
  {
    return new self($success, $message, $data);
  }
}