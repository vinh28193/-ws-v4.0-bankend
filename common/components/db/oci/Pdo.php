<?php


namespace common\components\db\oci;

use Yajra\Pdo\Oci8;

class Pdo extends Oci8
{

    public function __construct($dsn, $username, $password, array $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);
    }
}