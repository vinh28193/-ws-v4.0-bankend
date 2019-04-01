<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-14
 * Time: 14:43
 */

namespace common\models;

use common\models\db\Warehouse as DbWarehouse;

class Warehouse extends DbWarehouse
{
    const GROUP_WAREHOUSE_TRANSIT = 1;
    const GROUP_WAREHOUSE_SAVE = 2;
    const GROUP_WAREHOUSE_NOTE_PURCHASE = 3;
}
