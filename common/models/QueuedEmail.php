<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 2/23/2018
 * Time: 3:24 PM
 */

namespace common\models;

use common\models\db\QueuedEmail as DbQueuedEmail;
use common\models\db\EmailAccount;
use Yii;
use yii\swiftmailer\Mailer;

class QueuedEmail extends DbQueuedEmail
{
    /**
     *
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $vars
     * @param string $layout
     */
    public static function createQueueEmail($to, $subject, $template, $storeId, $vars = array(), $layout = 'main', $from = null, $formName = null)
    {
        $queuedEmail = new QueuedEmail();
        if ($from) {
            $queuedEmail->From = $from;
        } else {
            $emailAccount = EmailAccount::findOne(['StoreId' => $storeId]);
            $queuedEmail->From = isset($emailAccount->Email) ? $emailAccount->Email : 'support@weshop.com.vn';
        }
        switch ($storeId) {
            case 6:
                $template = $template . '_my';
                break;
            case 7:
                $template = $template . '_id';
                break;
            case 8:
                $template = $template . '_sg';
                break;
            case 9:
                $template = $template . '_ph';
                break;
            case 10:
                $template = $template . '_th';
                break;
        }
        if($formName){
            $queuedEmail->FromName = $formName;
        }else{
            $queuedEmail->FromName = isset($emailAccount->DisplayName) ? $emailAccount->DisplayName : 'Weshop Global';
        }
        if (strpos(strtolower($to), strtolower('Haiah6868@gmail.com')) !== false) {
            $to = 'chiennd@peacesoft.net';
        }
        $queuedEmail->To = $to;
        $queuedEmail->Subject = $subject;
        $queuedEmail->Body = self::render($template, $vars, $layout);
        if (isset($vars['order']->id) && !empty($vars['order']->id)) {
            $queuedEmail->OrderId = $vars['order']->id;
        }
        $queuedEmail->CreatedTime = date("Y-m-d H:i:s");
        if (isset($emailAccount->id)) {
            $queuedEmail->EmailAccountId = $emailAccount->id;
        }
        $queuedEmail->Opened = 0;
        $queuedEmail->Openedon = date("Y-m-d H:i:s");
        $queuedEmail->Status = 0;
        if ($queuedEmail->save(false)) {
            unset($queuedEmail);
            return true;
        } else {
            unset($queuedEmail);
            return false;
        }
    }


    public function checkExits($orderId)
    {
        $find = QueuedEmail::find()->where(['OrderId' => $orderId])->count();
        if ($find > 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $template
     * @param array $vars
     * @param type $baseUrl
     * @param type $layout
     * @return type
     */
    public static function render($template, $vars, $layout = 'main')
    {
        $mailer = new Mailer();
        $layout = '@mail/layouts/' . $layout;
        return $mailer->render('@mail/views/weshop/emailtemps/' . $template, $vars, $layout);

    }

}