<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Authors $model */
/** @var array $books List of books to choose from */

$this->title = 'Update Authors: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'books' => $books,
    ]) ?>

</div>
