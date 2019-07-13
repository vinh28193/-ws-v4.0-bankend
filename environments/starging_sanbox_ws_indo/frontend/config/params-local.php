<?php
return [
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail'=> 'csadmin@weshop.com.vn',
    'Url_User_Back_end'=>'http://s.weshop.asia',
    'Url_FrontEnd'=>'http://v3.weshop.com.vn',
    'Url_wallet_api' => 'http://v3.weshop.com.vn',
    'ENV' => true, // True --> envaroment Develop , false : Prod
    'api_login_boxme' =>'http://sandbox.boxme.asia/api/v1/users/auth/sign-in/',
    'api_addresse_warehouse' => 'http://sandbox.boxme.asia/api/v1/sellers/addresses/default-warehouse/',
    'pickupUSWHGlobal' => [
        'default' => 'ws_id',
    ]
];
