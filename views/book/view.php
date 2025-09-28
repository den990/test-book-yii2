<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => ['confirm' => 'Are you sure?', 'method' => 'post'],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'isbn',
            'description:ntext',
            [
                'attribute' => 'cover',
                'format' => 'html',
                'value' => function($model) {
                    return $model->cover
                        ? '<img src="' . \yii\helpers\Html::encode($model->cover) . '" style="max-width:200px;">'
                        : null;
                },
            ],
            [
                'label' => 'Авторы',
                'value' => implode(', ', $model->getAuthors()->select('full_name')->column()),
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
