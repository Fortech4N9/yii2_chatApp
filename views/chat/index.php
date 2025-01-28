<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $chats app\models\Chat[] */
/* @var $selectedChat app\models\Chat|null */
/* @var $messages app\models\Message[] */

$this->title = 'Chat';
?>
<div class="chat-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Search Users', ['search'], ['class' => 'btn btn-primary']) ?></p>
    <p><?= Html::a('Create Chat', ['create-chat'], ['class' => 'btn btn-success']) ?></p>

    <div class="chat-container d-flex">
        <div class="chat-list col-md-4">
            <h3>Chats</h3>
            <ul class="list-group">
                <?php foreach ($chats as $chat): ?>
                    <li class="list-group-item <?= $selectedChat && $selectedChat->id === $chat->id ? 'active' : '' ?>">
                        <?= Html::a(Html::encode($chat->name), ['index', 'chatId' => $chat->id]) ?>
                        <?= Html::beginForm(['delete-chat', 'chatId' => $chat->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::submitButton('Delete', ['class' => 'btn btn-danger btn-sm', 'onclick' => 'return confirm("Are you sure you want to delete this chat?")']) ?>
                        <?= Html::endForm() ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="chat-view col-md-8">
            <?php if ($selectedChat): ?>
                <h3><?= Html::encode($selectedChat->name) ?></h3>
                <div class="messages" id="messages">
                    <?php foreach ($messages as $message): ?>
                        <div class="message">
                            <strong><?= Html::encode($message->user->username) ?>:</strong>
                            <p><?= Html::encode($message->content) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="send-message">
                    <?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['send-message', 'chatId' => $selectedChat->id]]); ?>
                        <?= $form->field(new \yii\base\DynamicModel(['content' => '']), 'content')->textInput(['name' => 'content']) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            <?php else: ?>
                <p>Select a chat to start messaging.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var conn = new WebSocket('ws://localhost:8080/chat');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        var data = JSON.parse(e.data);
        var messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.innerHTML = '<strong>' + data.username + ':</strong><p>' + data.content + '</p>';
        document.getElementById('messages').appendChild(messageElement);
    };

    document.getElementById('send-message').addEventListener('click', function(event) {
        event.preventDefault();
        var content = document.getElementById('message-content').value;
        var username = "<?= Yii::$app->user->identity->username ?>";
        conn.send(JSON.stringify({
            username: username,
            content: content
        }));

        // Clear the input field
        document.getElementById('message-content').value = '';
    });
</script>