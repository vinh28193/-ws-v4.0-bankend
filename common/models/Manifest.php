<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 06/03/2019
 * Time: 17:02
 */

namespace common\models;

use common\models\db\Manifest as DbManifest;

/**
 * Class Manifest
 * @package common\models
 * @property Package[] $packages
 */

class Manifest extends DbManifest
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $paren = parent::rules();
        $child = [
            ['active', 'default', 'value' => self::STATUS_ACTIVE],
            ['active', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
        return array_merge($paren,$child);
    }

}