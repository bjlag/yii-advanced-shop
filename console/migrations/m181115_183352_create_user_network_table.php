<?php

use yii\db\Migration;

/**
 * Class m181115_183352_user_network
 */
class m181115_183352_create_user_network_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_network}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'network' => $this->string()->notNull(),
            'identity' => $this->string()->notNull()
        ]);

        $this->addForeignKey('{{%fk-user_network-user_id-id}}', '{{%user_network}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('{{%idx-user_network-user_id}}', '{{%user_network}}', 'user_id');
        $this->createIndex('{{%idx-user-network_network-identity}}', '{{%user_network}}', ['network', 'identity'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_network}}');
    }
}
