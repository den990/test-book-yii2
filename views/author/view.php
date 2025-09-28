<?php

use app\models\Subscribe;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this author?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php else: ?>
        <p>
            <?= Html::button('Подписаться на автора', [
                'class' => 'btn btn-success',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#subscribeModal'
            ]) ?>
        </p>

        <?php
        Modal::begin([
            'title' => 'Подписка на автора',
            'id' => 'subscribeModal',
        ]);

        $subscribeModel = new Subscribe(['author_id' => $model->id]);
        $form = ActiveForm::begin([
            'action' => ['/author/subscribe', 'id' => $model->id],
            'method' => 'post',
        ]); ?>

        <?=  $form->field($subscribeModel, 'phone')->textInput(['placeholder' => '+79998887766']) ?>
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end();

        Modal::end(); ?>

    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

    <h3>Books by this Author</h3>
    <ul>
        <?php if (!empty($model->books)): ?>
            <?php foreach ($model->books as $book): ?>
                <li><?= Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]) ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <div>Empty</div>
        <?php endif; ?>
    </ul>

</div>
