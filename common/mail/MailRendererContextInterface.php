<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-02
 * Time: 15:14
 */

namespace common\mail;


interface MailRendererContextInterface
{
    /**
     * @param $template Template
     * @return mixed|void
     */
    public function extractTemplate($template);

    /**
     * @return string
     */
    public function render();
}