<?php

namespace common\models\db_old;

use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $fullName
 * @property string $identityImageUrl Link luu anh chung minh nhan dan
 * @property string $identityNumber
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $phone
 * @property int $gender
 * @property string $birthday
 * @property string $address
 * @property string $avatar
 * @property string $linkVerified Link khi đăng ký
 * @property string $EmailVerifyShipping Mail của customer khi đăng ký hàng ship
 * @property int $emailVerified
 * @property string $emailVerifiedTime
 * @property string $AdminComment Ghi chú của quản trị viên
 * @property int $Deleted Đã xóa hay chưa?
 * @property string $LastIpAddress Địa chỉ ip cuối hoạt động?
 * @property string $LastLoginTime Ngày đăng nhập cuối cùng
 * @property int $ManageOrganizationId
 * @property int $ManageDepartmentId
 * @property int $ManageEmployeeId
 * @property int $AffiliateId Thông tin đại lý
 * @property int $VendorId Thông tin nhà cung cấp
 * @property int $IsPersonal
 * @property string $CustomerRate
 * @property string $LastOrderDate
 * @property string $Mobile
 * @property string $EmailContact Email liên lạc khác
 * @property string $CompanyName
 * @property string $Job
 * @property string $Position Chức vụ
 * @property string $CompanyTaxCode
 * @property int $SourceId 1: Nguồn google, 2: fb, 3: email marketing, 4: trực tiếp, 5: khác
 * @property int $countryId
 * @property int $ProvinceId
 * @property int $DistrictId
 * @property string $village
 * @property int $TypeCustomerId
 * @property int $LanguageId Ngôn ngữ sử dụng khi đặt hàng để gửi email template
 * @property int $customerGroupId
 * @property int $active
 * @property string $updateTime
 * @property string $createTime
 * @property string $access_token
 * @property bool $receiveEmailMarketing Nhận email Marketing
 * @property int $suspended
 * @property string $authClient
 * @property string $verifyToken
 * @property string $passwordResetToken
 * @property int $oldId
 * @property int $storeId
 * @property string $SocialSecurityNo
 * @property string $MembershipLoyaltyNo
 * @property int $fromEbayVN
 * @property int $verifyShipping
 * @property string $note
 * @property int $type mặc định = 0 (customer weshop) ,  1 - customer ws-saleb-us
 * @property string $total_xu //Tổng số xu đang tích lũy trong quý
 * @property string $total_xu_start_date
 * @property string $total_xu_expired_date Thoi điểm reset điểm tích lũy về 0
 * @property string $usable_xu //tổng số xu có thể sử dụng (tgian 1 tháng)
 * @property string $usable_xu_start_date
 * @property string $usable_xu_expired_date //Thoi gian reset xu khả dụng  ve 0
 * @property string $last_use_xu
 * @property string $last_use_time
 * @property string $last_revenue_xu
 * @property string $last_revenue_time
 * @property string $locale
 * @property string $verify_code
 * @property int $verify_code_expired_at
 * @property int $verify_code_count
 * @property string $verify_code_type
 *
 * @property Address[] $addresses
 * @property AuctionInfos[] $auctionInfos
 * @property AuctionTransaction[] $auctionTransactions
 * @property AuthenticationProviders[] $authenticationProviders
 * @property CallDetail[] $callDetails
 * @property CallLog[] $callLogs
 * @property CouponLog[] $couponLogs
 * @property SystemCountry $country
 * @property Language $language
 * @property CustomerGroup $customerGroup
 * @property Store $store
 * @property SystemDistrict $district
 * @property SystemStateProvince $province
 * @property CustomerClaim[] $customerClaims
 * @property CustomerContact[] $customerContacts
 * @property CustomerFollowed[] $customerFolloweds
 * @property CustomerMembership[] $customerMemberships
 * @property CustomerPointItems[] $customerPointItems
 * @property CustomerPointTotal[] $customerPointTotals
 * @property CustomerWarehousePrefer[] $customerWarehousePrefers
 * @property CustomerXuLog[] $customerXuLogs
 * @property Invoice[] $invoices
 * @property NotificationSettingCustomer[] $notificationSettingCustomers
 * @property Order[] $orders
 * @property OrderAdditionFee[] $orderAdditionFees
 * @property OrderItemRefund[] $orderItemRefunds
 * @property PosCustomer[] $posCustomers
 * @property RequestPackages[] $requestPackages
 * @property RequestPackagesItems[] $requestPackagesItems
 * @property RequestShipment[] $requestShipments
 * @property Shipment[] $shipments
 * @property ShippingPackageLocal[] $shippingPackageLocals
 * @property SystemAccountTransaction[] $systemAccountTransactions
 * @property SystemAccountTransactionVoucher[] $systemAccountTransactionVouchers
 * @property Transaction[] $transactions
 * @property TransactionAccount[] $transactionAccounts
 * @property TransactionExternal[] $transactionExternals
 * @property TransactionQueue[] $transactionQueues
 * @property TransactionRefundDelegate[] $transactionRefundDelegates
 * @property TransactionRequest[] $transactionRequests
 * @property WarehousePackage[] $warehousePackages
 */
class Customer extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_old');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'emailVerified', 'Deleted', 'ManageOrganizationId', 'ManageDepartmentId', 'ManageEmployeeId', 'AffiliateId', 'VendorId', 'IsPersonal', 'SourceId', 'countryId', 'ProvinceId', 'DistrictId', 'TypeCustomerId', 'LanguageId', 'customerGroupId', 'active', 'suspended', 'oldId', 'storeId', 'fromEbayVN', 'verifyShipping', 'type', 'verify_code_expired_at', 'verify_code_count'], 'integer'],
            [['birthday', 'emailVerifiedTime', 'LastLoginTime', 'LastOrderDate', 'updateTime', 'createTime', 'total_xu_start_date', 'total_xu_expired_date', 'usable_xu_start_date', 'usable_xu_expired_date', 'last_use_time', 'last_revenue_time'], 'safe'],
            [['CustomerRate', 'total_xu', 'usable_xu', 'last_use_xu', 'last_revenue_xu'], 'number'],
            [['access_token', 'note'], 'string'],
            [['receiveEmailMarketing'], 'boolean'],
            [['firstName', 'lastName', 'email', 'SocialSecurityNo', 'MembershipLoyaltyNo'], 'string', 'max' => 100],
            [['fullName', 'identityImageUrl', 'EmailVerifyShipping', 'AdminComment', 'village', 'verify_code_type'], 'string', 'max' => 255],
            [['identityNumber', 'username', 'phone', 'LastIpAddress', 'Mobile', 'EmailContact', 'CompanyName', 'Job', 'Position', 'CompanyTaxCode', 'verifyToken', 'passwordResetToken'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 80],
            [['salt'], 'string', 'max' => 25],
            [['address', 'avatar'], 'string', 'max' => 500],
            [['linkVerified'], 'string', 'max' => 220],
            [['authClient'], 'string', 'max' => 15],
            [['locale', 'verify_code'], 'string', 'max' => 5],
            [['countryId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['countryId' => 'id']],
            [['LanguageId'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['LanguageId' => 'id']],
            [['customerGroupId'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerGroup::className(), 'targetAttribute' => ['customerGroupId' => 'id']],
            [['storeId'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['storeId' => 'id']],
            [['DistrictId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['DistrictId' => 'id']],
            [['ProvinceId'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['ProvinceId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'firstName' => Yii::t('db', 'First Name'),
            'lastName' => Yii::t('db', 'Last Name'),
            'fullName' => Yii::t('db', 'Full Name'),
            'identityImageUrl' => Yii::t('db', 'Identity Image Url'),
            'identityNumber' => Yii::t('db', 'Identity Number'),
            'email' => Yii::t('db', 'Email'),
            'username' => Yii::t('db', 'Username'),
            'password' => Yii::t('db', 'Password'),
            'salt' => Yii::t('db', 'Salt'),
            'phone' => Yii::t('db', 'Phone'),
            'gender' => Yii::t('db', 'Gender'),
            'birthday' => Yii::t('db', 'Birthday'),
            'address' => Yii::t('db', 'Address'),
            'avatar' => Yii::t('db', 'Avatar'),
            'linkVerified' => Yii::t('db', 'Link Verified'),
            'EmailVerifyShipping' => Yii::t('db', 'Email Verify Shipping'),
            'emailVerified' => Yii::t('db', 'Email Verified'),
            'emailVerifiedTime' => Yii::t('db', 'Email Verified Time'),
            'AdminComment' => Yii::t('db', 'Admin Comment'),
            'Deleted' => Yii::t('db', 'Deleted'),
            'LastIpAddress' => Yii::t('db', 'Last Ip Address'),
            'LastLoginTime' => Yii::t('db', 'Last Login Time'),
            'ManageOrganizationId' => Yii::t('db', 'Manage Organization ID'),
            'ManageDepartmentId' => Yii::t('db', 'Manage Department ID'),
            'ManageEmployeeId' => Yii::t('db', 'Manage Employee ID'),
            'AffiliateId' => Yii::t('db', 'Affiliate ID'),
            'VendorId' => Yii::t('db', 'Vendor ID'),
            'IsPersonal' => Yii::t('db', 'Is Personal'),
            'CustomerRate' => Yii::t('db', 'Customer Rate'),
            'LastOrderDate' => Yii::t('db', 'Last Order Date'),
            'Mobile' => Yii::t('db', 'Mobile'),
            'EmailContact' => Yii::t('db', 'Email Contact'),
            'CompanyName' => Yii::t('db', 'Company Name'),
            'Job' => Yii::t('db', 'Job'),
            'Position' => Yii::t('db', 'Position'),
            'CompanyTaxCode' => Yii::t('db', 'Company Tax Code'),
            'SourceId' => Yii::t('db', 'Source ID'),
            'countryId' => Yii::t('db', 'Country ID'),
            'ProvinceId' => Yii::t('db', 'Province ID'),
            'DistrictId' => Yii::t('db', 'District ID'),
            'village' => Yii::t('db', 'Village'),
            'TypeCustomerId' => Yii::t('db', 'Type Customer ID'),
            'LanguageId' => Yii::t('db', 'Language ID'),
            'customerGroupId' => Yii::t('db', 'Customer Group ID'),
            'active' => Yii::t('db', 'Active'),
            'updateTime' => Yii::t('db', 'Update Time'),
            'createTime' => Yii::t('db', 'Create Time'),
            'access_token' => Yii::t('db', 'Access Token'),
            'receiveEmailMarketing' => Yii::t('db', 'Receive Email Marketing'),
            'suspended' => Yii::t('db', 'Suspended'),
            'authClient' => Yii::t('db', 'Auth Client'),
            'verifyToken' => Yii::t('db', 'Verify Token'),
            'passwordResetToken' => Yii::t('db', 'Password Reset Token'),
            'oldId' => Yii::t('db', 'Old ID'),
            'storeId' => Yii::t('db', 'Store ID'),
            'SocialSecurityNo' => Yii::t('db', 'Social Security No'),
            'MembershipLoyaltyNo' => Yii::t('db', 'Membership Loyalty No'),
            'fromEbayVN' => Yii::t('db', 'From Ebay Vn'),
            'verifyShipping' => Yii::t('db', 'Verify Shipping'),
            'note' => Yii::t('db', 'Note'),
            'type' => Yii::t('db', 'Type'),
            'total_xu' => Yii::t('db', 'Total Xu'),
            'total_xu_start_date' => Yii::t('db', 'Total Xu Start Date'),
            'total_xu_expired_date' => Yii::t('db', 'Total Xu Expired Date'),
            'usable_xu' => Yii::t('db', 'Usable Xu'),
            'usable_xu_start_date' => Yii::t('db', 'Usable Xu Start Date'),
            'usable_xu_expired_date' => Yii::t('db', 'Usable Xu Expired Date'),
            'last_use_xu' => Yii::t('db', 'Last Use Xu'),
            'last_use_time' => Yii::t('db', 'Last Use Time'),
            'last_revenue_xu' => Yii::t('db', 'Last Revenue Xu'),
            'last_revenue_time' => Yii::t('db', 'Last Revenue Time'),
            'locale' => Yii::t('db', 'Locale'),
            'verify_code' => Yii::t('db', 'Verify Code'),
            'verify_code_expired_at' => Yii::t('db', 'Verify Code Expired At'),
            'verify_code_count' => Yii::t('db', 'Verify Code Count'),
            'verify_code_type' => Yii::t('db', 'Verify Code Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionInfos()
    {
        return $this->hasMany(AuctionInfos::className(), ['userId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionTransactions()
    {
        return $this->hasMany(AuctionTransaction::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthenticationProviders()
    {
        return $this->hasMany(AuthenticationProviders::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallDetails()
    {
        return $this->hasMany(CallDetail::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallLogs()
    {
        return $this->hasMany(CallLog::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponLogs()
    {
        return $this->hasMany(CouponLog::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'countryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'LanguageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGroup()
    {
        return $this->hasOne(CustomerGroup::className(), ['id' => 'customerGroupId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'storeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'DistrictId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'ProvinceId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerClaims()
    {
        return $this->hasMany(CustomerClaim::className(), ['customerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerContacts()
    {
        return $this->hasMany(CustomerContact::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerFolloweds()
    {
        return $this->hasMany(CustomerFollowed::className(), ['customerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerMemberships()
    {
        return $this->hasMany(CustomerMembership::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerPointItems()
    {
        return $this->hasMany(CustomerPointItems::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerPointTotals()
    {
        return $this->hasMany(CustomerPointTotal::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerWarehousePrefers()
    {
        return $this->hasMany(CustomerWarehousePrefer::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerXuLogs()
    {
        return $this->hasMany(CustomerXuLog::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationSettingCustomers()
    {
        return $this->hasMany(NotificationSettingCustomer::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderAdditionFees()
    {
        return $this->hasMany(OrderAdditionFee::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemRefunds()
    {
        return $this->hasMany(OrderItemRefund::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosCustomers()
    {
        return $this->hasMany(PosCustomer::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestPackages()
    {
        return $this->hasMany(RequestPackages::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestPackagesItems()
    {
        return $this->hasMany(RequestPackagesItems::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestShipments()
    {
        return $this->hasMany(RequestShipment::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipments()
    {
        return $this->hasMany(Shipment::className(), ['customerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShippingPackageLocals()
    {
        return $this->hasMany(ShippingPackageLocal::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemAccountTransactions()
    {
        return $this->hasMany(SystemAccountTransaction::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSystemAccountTransactionVouchers()
    {
        return $this->hasMany(SystemAccountTransactionVoucher::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionAccounts()
    {
        return $this->hasMany(TransactionAccount::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionExternals()
    {
        return $this->hasMany(TransactionExternal::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionQueues()
    {
        return $this->hasMany(TransactionQueue::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionRefundDelegates()
    {
        return $this->hasMany(TransactionRefundDelegate::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionRequests()
    {
        return $this->hasMany(TransactionRequest::className(), ['CustomerId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehousePackages()
    {
        return $this->hasMany(WarehousePackage::className(), ['CustomerId' => 'id']);
    }
}
