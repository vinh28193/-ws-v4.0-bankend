<?php

namespace wallet\modules\auth\traits;

trait ClassNamespace
{
    public static function className()
    {
        return get_called_class();
    }
}