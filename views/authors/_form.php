<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Authors $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $books Список книг для выбора */
?>

<div class="authors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bookIds')->listBox(
        $books,
        [
            'multiple' => true,
            'size' => 10,
            'options' => [
                'class' => 'form-control',
                'style' => 'height: 150px;'
            ]
        ]
    )->label('Books') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
