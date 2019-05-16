<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 09:31
 */

namespace common\mail;


use common\models\QueuedEmail;

class MailTarget extends BaseTarget
{

    public $id = 'mail';
    public $type;
    public $to_address;
    public $from_address;
    public $from_name;
    public $subject;
    public $html_content;
    /**
     * @param Template $template
     * @return mixed|void
     */
    public function prepare($template)
    {
        $this->type = $template->type;
        if($template->to_address !== null){
            $this->to_address  = $template->to_address;
        }
        $this->from_address = $template->from_address;
        $this->from_name = $template->from_name;
        $this->subject = $template->getSubjectReplace();
        $this->html_content = $template->getHtmlContentReplace();
    }
    /**
     * @param string $receive


     * @return mixed|void
     */
    public function handle($receive)
    {
        if($this->to_address !== null && $this->type === Template::TYPE_CONTACT_ADMIN){
            $receive = $this->to_address;
        }
        if($receive !== null && $receive !== ''){
            $queuedEmail = new QueuedEmail();
            $queuedEmail->From = $this->from_address;
            $queuedEmail->FromName = $this->from_name;
            $queuedEmail->To = $receive;
            $queuedEmail->Subject = $this->subject;
            $queuedEmail->Body = $this->html_content;
            $queuedEmail->Opened = 0;
            $queuedEmail->Status = 0;
            $queuedEmail->CreatedTime = \Yii::$app->formatter->asDatetime('now');
            $queuedEmail->Openedon = \Yii::$app->formatter->asDatetime('now');
            $queuedEmail->save(false);
        }

    }
}