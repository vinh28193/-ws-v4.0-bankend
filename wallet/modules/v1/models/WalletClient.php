<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/2/2018
 * Time: 9:34 AM
 */

namespace wallet\modules\v1\models;


use common\components\Cache;
use wallet\modules\v1\base\interfaces\IWallet;
use wallet\modules\v1\base\interfaces\IWalletClient;
use wallet\modules\v1\base\interfaces\IWalletConfig;
use wallet\modules\v1\models\enu\ResponseCode;
use wallet\modules\v1\models\traits\WalletClientTrait;
use wallet\modules\v1\models\traits\WalletOauthTrait;
use wallet\modules\v1\Module;
use OAuth2\Storage\ClientCredentialsInterface;
use OAuth2\Storage\UserCredentialsInterface;
use Yii;
use yii\web\IdentityInterface;

/**
 * Class WalletClient
 * @package wallet\modules\v1\models
 *
 * @property-read $publicProfile array
 */
class WalletClient extends \common\models\db\WalletClient implements IdentityInterface, UserCredentialsInterface, ClientCredentialsInterface, IWalletClient, IWalletConfig, IWallet
{
    use WalletOauthTrait;
    use WalletClientTrait;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_FREEZE = 2;

    public function afterFind()
    {
        parent::afterFind();
    }

    public static function getByCustomerId($customerId, $cache = false)
    {
        $key = !empty(Yii::$app->params['CACHE_WALLET_CLIENT_BY_CUSTOMER_ID_']) ? Yii::$app->params['CACHE_WALLET_CLIENT_BY_CUSTOMER_ID_'] : 'CACHE_WALLET_CLIENT_BY_CUSTOMER_ID_';
        $key = $key . $customerId;
        $data = Cache::get($key);
        if (!$data || $cache == false) {
            $data = @self::find()->where(['customer_id' => $customerId])->one();
            Cache::set($key, $data, 60);
        }

        if (!empty($data)) {
            return $data;
        }
        return ['code' => ResponseCode::ERROR, 'message' => 'Not exist wallet', 'data' => null];

    }

    public function getPublicProfile(){
        $attributes = [
            'username',
            'customer_phone',
            'email',
            'current_bulk_point',
            'store_id',
            'current_balance',
            'freeze_balance',
            'usable_balance',
            'withdrawable_balance',
            'status'
        ];
        return $this->getAttributes($attributes);
    }
}