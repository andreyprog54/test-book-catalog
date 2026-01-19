<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthorSubscription $model */
/** @var app\models\Authors $author */

$this->title = 'Subscribe to ' . Html::encode($author->name);
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $author->name, 'url' => ['view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Subscribe';
?>
<div class="author-subscribe">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="author-subscribe-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Subscribe', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>