<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Books $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $authors Список авторов для выбора */
?>

<div class="books-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coverFile')->fileInput() ?>

    <?php if ($model->cover_image): ?>
        <div class="mb-3">
            <?= Html::img($model->cover_image, ['style' => 'max-width: 200px; height: auto;', 'alt' => 'Current cover']) ?>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'authorIds')->listBox(
        $authors,
        [
            'multiple' => true,
            'size' => 10,
            'options' => [
                'class' => 'form-control',
                'style' => 'height: 150px;'
            ]
        ]
    )->label('Authors') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
