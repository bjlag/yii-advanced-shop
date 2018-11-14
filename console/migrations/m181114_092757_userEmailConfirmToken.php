<?php

use yii\db\Migration;

/**
 * Class m181114_092757_userEmailConfirmToken
 */
class m181114_092757_userEmailConfirmToken extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'email_confirm_token');

        return true;
    }
}
