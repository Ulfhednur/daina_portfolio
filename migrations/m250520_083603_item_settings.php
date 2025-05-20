<?php

use yii\db\Migration;

class m250520_083603_item_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('items', 'settings', $this->json()->notNull()->defaultValue('{}'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('settings', 'items');
    }
}
