
#-----------------Ket noi Boxme Lấy hạng -------------------------------
 1. Loại Hạng cua KH 
     'loyalty' => [ 
                'user_id' => 254597,
                'country_code' => 'VN',
                'user_level' => 1,
                'time_end' => 0,
            ],
 2. Lưu lại địa chỉ kho cua khách hàng có hạng đó ( Mỗi một User có 1 địa chỉ kho khác nhau)  
 3. Lưu ý : Các Khách hàng thường của Weshop --> có 1 tài khoản nên chỉ có một địa chỉ kho         
            
[
    'dataRs' => [
        'data' => [
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoyNTQ1OTcsInBhcmVudF9pZCI6MCwidXNlcm5hbWUiOiJ3c190ZXN0QGdtYWlsLmNvbSIsImZ1bGxuYW1lIjoiV2Ugc2hvcCIsImNvdW50cnkiOiJWTiIsImVtYWlsIjoid3NfdGVzdEBnbWFpbC5jb20iLCJ0aW1lem9uZSI6IkFzaWEvSG9fQ2hpX01pbmgiLCJjdXJyZW5jeSI6IlZORCIsIm1lYXN1cmVtZW50IjowLCJwaW5fY29kZSI6NDMyNTE4LCJwcml2aWxlZ2UiOjAsInJvbGUiOjIsImlzX3ZlcmlmeV9zdGFmZiI6MCwiaXNfc3VwZXJ1c2VyIjpmYWxzZSwiaXNfc3RhZmYiOmZhbHNlLCJleHAiOjE1NjMwNzM5NDEsIm9yaWdfaWF0IjoxNTYyOTg3NTQxLCJncm91cF9pZCI6MH0.gA_kbLJBS6DeBHUWE7lmW-VvaOVd3nh3fxQxCRKizjI',
            'is_profile' => 1,
            'is_inventory' => 1,
            'is_payment' => 0,
            'id' => 47228,
            'user' => [
                'id' => 254597,
                'email' => 'ws_test@gmail.com',
                'username' => 'ws_test',
                'fullname' => 'We shop',
                'avatar' => '',
                'parent_id' => 0,
                'parent_email' => '',
                'invite_code' => 'VLMIZ',
                'invite_by' => null,
                'is_superuser' => false,
                'is_staff' => false,
                'is_verify_staff' => 0,
                'is_active' => true,
                'created_time' => 1562983456,
                'updated_time' => 1562983456,
                'last_login' => 1562987542,
                'pin_code' => 432518,
                'is_profile_update_required' => false,
            ],
            'group' => null,
            'role' => 2,
            'privilege' => 0,
            'country' => 'VN',
            'lang' => 'vi',
            'currency' => 'VND',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'phone_number' => '0987654321',
            'address' => '',
            'measurement' => 0,
            'layers_security' => 0,
            'layers_security_gg' => 0,
            'serect_key_gg' => 'STDBJWZmHTGgcWab',
            'notification' => 0,
            'type_notice' => '',
            'email_notice' => '',
            'phone_notice' => '',
            'facebook_notice' => '',
            'daily_report_notice' => 0,
            'received_shipment_notice' => 0,
            'closed_shipment_notice' => 0,
            'inventory_notice' => 0,
            'pipe_status' => 100,
            'priority_payment' => 2,
            'is_vip' => 0,
            'level' => 0,
            'active' => 1,
            'verified' => 1,
            'api_access_token' => '',
            'android_device_token' => '',
            'ios_device_token' => '',
            'sip_account' => '',
            'sip_pwd' => '',
            'refer_code' => '',
            'fulfillment' => 0,
            'config_service' => 0,
            'config_courier' => 0,
            'loyalty' => [
                'user_id' => 254597,
                'country_code' => 'VN',
                'user_level' => 1,
                'time_end' => 0,
            ],
            'credit_limit' => null,
            'credit_approve_track' => 1,
            'credit_current' => 0,
            'config_inventory' => 0,
            'num_contract' => '',
            'active_contract_time' => 0.0,
            'created_time' => 1562917552,
            'updated_time' => 1562987541,
            'merchant' => [
                'user_id' => 254597,
                'country_code' => 'VN',
                'home_currency' => 'VND',
                'user_level' => 1.0,
                'balance_pvc' => 0.0,
                'balance_cod' => 0.0,
                'provisional' => 0.0,
                'freeze' => 0.0,
                'quota' => 0.0,
                'money_available' => 0.0,
                'reward_point' => 0.0,
                'balance_config' => 0.0,
            ],
            'currency_exchange' => [],
            'profile_saved' => false,
            'ops_group' => [],
            'extend_service' => null,
            'is_block_reward' => 0,
        ],
        'error' => false,
        'error_code' => '',
        'messages' => '',
        'total' => 0,
    ],
]


#--------------Khi Khách hàng có hang  + Liên Kết Boxme Me ------------------
1. khi Kh login --> gọi gRPC check hạng + thời gian kết thúc
2. lấy địa chỉ kho của từng khách hàng dùng để tính toán + phí vận chuyển quốc tế theo hạng + Phí bảo hiểm cũng theo hạng
Thinh Nguyen, [13.07.19 13:58]
sandbox.boxme.asia/api/v1/sellers/addresses/default-warehouse/

Thinh Nguyen, [13.07.19 13:58]
{
  "token": "8f6df519a2125946820bc34a561164c2",
  "country": "VN",
  "user_id": 1032,
  "phone": "00987654321",
  "fullname": "david"
}

Thinh Nguyen, [13.07.19 13:58]
API lấy kho của user
