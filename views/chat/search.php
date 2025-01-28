<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $users app\models\UserSearch[] */
/* @var $query string */

$this->title = 'Search Users';
?>
<div class="chat-search">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['search']]); ?>
        <?= $form->field(new \yii\base\DynamicModel(['query' => $query]), 'query')->textInput(['name' => 'query']) ?>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php if (!empty($users)): ?>
        <h2>Results:</h2>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <?= Html::encode($user->username) ?> (<?= Html::encode($user->email) ?>)
                    <?= Html::a('Create Chat', ['create-chat', 'usernameOrEmail' => $user->username], ['class' => 'btn btn-success']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>