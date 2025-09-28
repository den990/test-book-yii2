<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Author[] $topAuthors */
/** @var int $year */

$this->title = "TOP 10 authors $year";
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['top-authors'],
]); ?>
<div class="row mb-3">
    <div class="col-md-3">
        <input type="number" name="year" class="form-control" placeholder="Год" value="<?= Html::encode($year) ?>" min="1900" max="<?= date('Y') ?>">
    </div>
    <div class="col-md-2">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Author</th>
        <th>Count book</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($topAuthors as $i => $author): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= Html::encode($author->full_name) ?></td>
            <td><?= Html::encode(count($author->books)) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
