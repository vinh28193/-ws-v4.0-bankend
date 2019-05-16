<?php


namespace common\payment\providers\wallet;

use common\payment\providers\vietnam\NganLuongProvider;

class WalletHideProvider extends NganLuongProvider
{

    public function handle($data)
    {
        parent::handle($data);
    }
}