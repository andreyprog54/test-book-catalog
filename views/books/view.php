<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Books $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'description:ntext',
            'isbn',
            'cover_image',
            [
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function($model) {
                    return $model->cover_image ? Html::img($model->cover_image, ['style' => 'max-width: 200px;']) : null;
                },
            ],
            [
                'label' => 'Authors',
                'format' => 'raw',
                'value' => function($model) {
                    if (empty($model->authors)) {
                        return 'No authors found';
                    }

                    $authors = [];
                    foreach ($model->authors as $author) {
                        $authors[] = Html::a(Html::encode($author->name), ['/authors/view', 'id' => $author->id]);
                    }

                    return implode('<br>', $authors);
                },
            ],
        ],
    ]) ?>

</div>
