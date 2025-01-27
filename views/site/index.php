<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $userAttributes array|null */

$this->title = 'Home';
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($userAttributes): ?>
        <h2>User Information</h2>
        <h3>Name: <?=$userAttributes['username']?></h3>
        <h3>Email: <?=$userAttributes['email']?></h3>
    <?php else: ?>
        <p>You are not logged in.</p>
        <p><?= Html::a('Login', ['/user/login'], ['class' => 'btn btn-primary']) ?></p>
        <p><?= Html::a('Signup', ['/user/signup'], ['class' => 'btn btn-success']) ?></p>
    <?php endif; ?>
</div>