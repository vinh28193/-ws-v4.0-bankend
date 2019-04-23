<?php


namespace common\boxme;


class WarehouseClient extends BaseClient
{

    public $env = self::ENV_PRODUCT;
    public $params = [
        self::ENV_PRODUCT => [
            'api_key' => 'Q9v5AX0JsM5nLWUs3zDt8YQN3z9a55qP',
            'base_url' => 'https://wms.boxme.asia/v1/packing',
        ],
        self::ENV_SANDBOX => [
            'api_key' => 'Q9v5AX0JsM5nLWUs3zDt8YQN3z9a55qP',
            'base_url' => 'http://wmsv2.boxme.vn/v1/packing',
        ]
    ];

    public function init()
    {
        parent::init();
    }

    protected function getAuthorization()
    {
        return ['Authorization' => $this->api_key];
    }

    public function detail($page = 1, $q = null)
    {

    }

    public function create($params)
    {

    }
}