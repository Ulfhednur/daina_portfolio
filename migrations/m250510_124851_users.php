<?php

use yii\db\Migration;

class m250510_124851_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned(),
            'login' => $this->string(255),
            'user_password' => $this->string(64),
            'access_token' => $this->string(255),
            'auth_key' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
