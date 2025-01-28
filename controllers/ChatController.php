<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\UserSearch;
use app\models\User;
use app\models\Chat;
use yii\filters\AccessControl;
use app\models\Message;
use app\models\ChatParticipant;

class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'search', 'create-chat', 'view-chat', 'send-message', 'add-participant', 'complete-chat'],
                'rules' => [
                    [
                        'actions' => ['index', 'search', 'create-chat', 'view-chat', 'send-message', 'add-participant', 'complete-chat'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($chatId = null)
    {
        $userId = Yii::$app->user->id;
        $chats = Chat::find()
            ->joinWith('participants')
            ->where(['chat_participants.user_id' => $userId])
            ->all();

        $selectedChat = null;
        $messages = [];

        if ($chatId) {
            $selectedChat = Chat::findOne($chatId);
            if ($selectedChat && !$selectedChat->isParticipant($userId)) {
                throw new \yii\web\ForbiddenHttpException('You are not a participant of this chat.');
            }
            $messages = $selectedChat ? $selectedChat->messages : [];
        }

        return $this->render('index', [
            'chats' => $chats,
            'selectedChat' => $selectedChat,
            'messages' => $messages,
        ]);
    }

    public function actionSearch($query = '')
    {
        $users = UserSearch::searchUsers($query);

        return $this->render('search', [
            'users' => $users,
            'query' => $query,
        ]);
    }

    public function actionCreateChat()
    {
        $model = new \yii\base\DynamicModel(['name']);
        $model->addRule(['name'], 'required');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $chat = Chat::createChat($model->name, [Yii::$app->user->id]);
            if ($chat) {
                return $this->redirect(['add-participant', 'chatId' => $chat->id]);
            }
        }

        return $this->render('create-chat', [
            'model' => $model,
        ]);
    }

    public function actionAddParticipant($chatId, $query = '')
    {
        $chat = Chat::findOne($chatId);
        if (!$chat || !$chat->isParticipant(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('You are not a participant of this chat.');
        }
    
        $users = UserSearch::searchUsers($query);
    
        if (Yii::$app->request->isPost) {
            $userNames = Yii::$app->request->post('userNames', []);
            foreach ($userNames as $username) {
                $user = User::findOne(['username' => $username]);
                if ($user && $user->id != Yii::$app->user->id && !$chat->isParticipant($user->id)) {
                    $participant = new ChatParticipant();
                    $participant->chat_id = $chat->id;
                    $participant->user_id = $user->id;
                    $participant->save();
                }
            }
            return $this->redirect(['add-participant', 'chatId' => $chat->id]);
        }
    
        return $this->render('add-participant', [
            'chat' => $chat,
            'users' => $users,
            'query' => $query,
        ]);
    }

    public function actionCompleteChat($chatId)
    {
        $chat = Chat::findOne($chatId);
        if (!$chat || !$chat->isParticipant(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('You are not a participant of this chat.');
        }

        return $this->redirect(['index', 'chatId' => $chat->id]);
    }

    public function actionSendMessage($chatId)
    {
        $chat = Chat::findOne($chatId);
        if (!$chat) {
            throw new \yii\web\NotFoundHttpException('Chat not found.');
        }

        if (!$chat->isParticipant(Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('You are not a participant of this chat.');
        }

        if (Yii::$app->request->isPost) {
            $content = Yii::$app->request->post('content');
            if ($content) {
                $message = new Message();
                $message->chat_id = $chat->id;
                $message->user_id = Yii::$app->user->id;
                $message->content = $content;
                $message->save();
            }
        }

        return $this->redirect(['index', 'chatId' => $chat->id]);
    }
}