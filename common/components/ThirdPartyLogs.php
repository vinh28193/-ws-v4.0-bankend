<?php


namespace common\components;


use DateTime;
use DateTimeZone;
use Yii;

class ThirdPartyLogs extends \common\modelsMongo\ThirdPartyLogs
{
    const PROVIDER_SC_BOXME = 'SC_BOXME';
    const PROVIDER_NGANLUONG = 'NL';
    const PROVIDER_NEXTMO = 'SMS_NEXTMO';
    const PROVIDER_SMS_VN = 'SMS_VN';

    public static function getLogs($date = null, $provider = null, $action = null)
    {
        $rs = \backend\models\ThirdPartyLogs::find();
        if ($provider != null) {
            $rs->andWhere(['provider' => $provider]);
        }
        if ($date != null) {
            $rs->andWhere(['date' => $date]);
        }
        if ($action != null) {
            $rs->andWhere(['action' => $action]);

        }
        $r = $rs->asArray()->all();
        if (count($r) > 0) return $r;
        return null;

    }

    public static function setLog($provider, $action, $user, $request, $response)
    {
        $date = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
        try {
            $log = new ThirdPartyLogs();
            $log->date = $date->format('Y-m-d');
            $log->create_by = $user;
            $log->create_time = $date->format('Y-m-d H:i:s');
            $log->action = $action;
            $log->request = $request;
            $log->response = $response;
            $log->provider = $provider;
            $log->save(false);
            return true;
        } catch (\Exception $e) {
            Yii::info($e);
            return false;
        }

//        return self::getLogs($log->date, $provider, $action);
    }
}