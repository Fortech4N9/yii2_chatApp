<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Message[] $messages
 * @property ChatParticipant[] $participants
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Participants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipants()
    {
        return $this->hasMany(ChatParticipant::class, ['chat_id' => 'id']);
    }

    /**
     * Creates a new chat with the given name and participants.
     *
     * @param string $name
     * @param array $userIds
     * @return Chat|null
     */
    public static function createChat($name, $userIds)
    {
        $chat = new self();
        $chat->name = $name;
        if ($chat->save()) {
            foreach ($userIds as $userId) {
                $participant = new ChatParticipant();
                $participant->chat_id = $chat->id;
                $participant->user_id = $userId;
                $participant->save();
            }
            return $chat;
        }
        return null;
    }

    /**
     * Checks if the user is a participant of the chat.
     *
     * @param int $userId
     * @return bool
     */
    public function isParticipant($userId)
    {
        return $this->getParticipants()->where(['user_id' => $userId])->exists();
    }
}