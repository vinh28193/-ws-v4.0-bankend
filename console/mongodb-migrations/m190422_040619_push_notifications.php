<?php

class m190422_040619_push_notifications extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createCollection(['weshop_global_40', 'push_notifications']);
    }

    public function down()
    {
        $this->dropCollection(['weshop_global_40', 'push_notifications']);
    }
    /**
     * db.createCollection(['Weshop_log_40','push_notifications'],
     * {
     * validator: {
     * $and: [
     * { token: { $type: "string" } },
     * { token: { $exists: true } },
     * { subscribed_on: { $type: "date" } },
     * { subscribed_on: { $exists: true } },
     * { user_id: { $type: "int" } },
     * { user_id: { $exists: true } },
     * { fingerprint: { $type: "string" } },
     * { fingerprint: { $exists: true } }
     * ]
     * }
     * }
     * )
     */
}
