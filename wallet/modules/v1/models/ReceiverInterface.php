<?php


namespace common\mail;


interface ReceiverInterface
{
    public function extract($target);
}