<?php

use yii\db\Migration;

class m250520_101450_media_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('media', 'settings', $this->json()->notNull()->defaultValue('{}'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('settings', 'media');
    }
}
