<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\searches\AuthorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'action' => ['/author/index'],
        'method' => 'get',
        'fieldConfig' => [
            'options' => [
                'tag' => false
            ]
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($searchModel, 'name')->label(false)->textInput(['placeholder' => $searchModel->getAttributeLabel('name')]) ?>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <?= Html::submitButton(
                    Yii::t('app', 'Find'), ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['/author/index'], ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <p>
        <?= Html::a('Create Author', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'full_name',
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'view' => true,
                    'update' => !Yii::$app->user->isGuest,
                    'delete' => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]) ?>

</div>
