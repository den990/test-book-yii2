<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Author;

/* @var $this yii\web\View */
/* @var $model app\models\forms\BookForm */
/* @var $form yii\widgets\ActiveForm */

$authors = Author::find()->select(['full_name', 'id'])->indexBy('id')->column();
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'year')->input('number') ?>
    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'cover')->textInput(['maxlength' => true, 'placeholder' => 'URL обложки']) ?>

    <?= $form->field($model, 'authorIds')->checkboxList($authors, ['class' => 'd-flex flex-column']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
