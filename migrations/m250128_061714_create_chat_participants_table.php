<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_participants}}`.
 */
class m250128_061714_create_chat_participants_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_participants}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `chat_id`
        $this->createIndex(
            '{{%idx-chat_participants-chat_id}}',
            '{{%chat_participants}}',
            'chat_id'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-chat_participants-user_id}}',
            '{{%chat_participants}}',
            'user_id'
        );

        // add foreign key for table `{{%chat}}`
        $this->addForeignKey(
            '{{%fk-chat_participants-chat_id}}',
            '{{%chat_participants}}',
            'chat_id',
            '{{%chat}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-chat_participants-user_id}}',
            '{{%chat_participants}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-chat_participants-chat_id}}',
            '{{%chat_participants}}'
        );

        $this->dropForeignKey(
            '{{%fk-chat_participants-user_id}}',
            '{{%chat_participants}}'
        );

        $this->dropIndex(
            '{{%idx-chat_participants-chat_id}}',
            '{{%chat_participants}}'
        );

        $this->dropIndex(
            '{{%idx-chat_participants-user_id}}',
            '{{%chat_participants}}'
        );

        $this->dropTable('{{%chat_participants}}');
    }
}
