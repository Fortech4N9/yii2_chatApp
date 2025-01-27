<?php

use yii\db\Migration;

/**
 * Class m250127_153358_add_password_hash_to_user_table
 */
class m250127_153358_add_password_hash_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'password_hash', $this->string()->notNull()->after('email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'password_hash');

        return false;
    }
}
