<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'action' => ['/book/index'],
        'method' => 'get',
        'fieldConfig' => [
            'options' => [
                'tag' => false
            ]
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($searchModel, 'title')->label(false)->textInput(['placeholder' => $searchModel->getAttributeLabel('title')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'year')->label(false)->textInput(['placeholder' => $searchModel->getAttributeLabel('year')]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'isbn')->label(false)->textInput(['placeholder' => $searchModel->getAttributeLabel('isbn')]) ?>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?= Html::submitButton(
                    Yii::t('app', 'Find'), ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['/book/index'], ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Create book', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'year',
            'isbn',
            [
                'label' => 'Authors',
                'value' => function (\app\models\Book $model) {
                    return implode(', ', $model->getAuthors()->select('full_name')->column());
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'view' => true,
                    'update' => !Yii::$app->user->isGuest,
                    'delete' => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]); ?>

</div>
