<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/8/2018
 * Time: 11:27 AM
 */
namespace wallet\modules\v1\base\lib;
class Email
{
    public function sendMail()
    {
        try {
            $elasticEmail = new \ElasticEmail\ElasticEmailV2('your_elastic_api_key');

            $rs = $elasticEmail->email()->send([
                'from' => 'from_email',
                'to' => 'to_email',
                'subject' => 'subject',
                'bodyHtml' => 'html'
            ]);
        } catch (\ElasticEmail\Exceptions\ElasticEmailV2Exception $e) {
        }


    }
}