<?php

use yii\db\Migration;

/**
 * Class m181119_154750_table_user_change_not_null
 */
class m181119_154750_table_user_change_not_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string()->null());
        $this->alterColumn('{{%user}}', 'password_hash', $this->string()->null());
        $this->alterColumn('{{%user}}', 'email', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%user}}', 'username', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'password_hash', $this->string()->notNull());
        $this->alterColumn('{{%user}}', 'email', $this->string()->notNull());
    }
}
