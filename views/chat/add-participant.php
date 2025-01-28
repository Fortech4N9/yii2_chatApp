<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $chat app\models\Chat */
/* @var $users app\models\UserSearch[] */
/* @var $query string */

$this->title = 'Add Participants to ' . $chat->name;
?>
<div class="chat-add-participant">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['add-participant', 'chatId' => $chat->id]]); ?>
        <?= $form->field(new \yii\base\DynamicModel(['query' => $query]), 'query')->textInput(['name' => 'query']) ?>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php if (!empty($users)): ?>
        <h2>Results:</h2>
        <?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['add-participant', 'chatId' => $chat->id]]); ?>
            <?php foreach ($users as $user): ?>
                <div>
                    <?= Html::checkbox('userNames[]', false, ['value' => $user->username]) ?>
                    <?= Html::encode($user->username) ?> (<?= Html::encode($user->email) ?>)
                </div>
            <?php endforeach; ?>
            <div class="form-group">
                <?= Html::submitButton('Add to Chat', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>

    <p><?= Html::a('Complete Chat', ['complete-chat', 'chatId' => $chat->id], ['class' => 'btn btn-primary']) ?></p>
</div>
