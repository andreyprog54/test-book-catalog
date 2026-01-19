<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $authors array */
/* @var $year integer */

$this->title = "Top 10 Authors for $year";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top10authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="form-group">
        <?= Html::beginForm([''], 'get') ?>
        <div class="input-group">
            <?= Html::input('number', 'year', $year, [
                    'class' => 'form-control',
                    'min' => 1900,
                    'max' => date('Y'),
                    'step' => 1
            ]) ?>
            <span class="input-group-btn">
                    <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
                </span>
        </div>
        <?= Html::endForm() ?>
    </div>

    <?php if (empty($authors)): ?>
        <div class="alert alert-info">No authors found for the selected year.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Author</th>
                <th>Books Count</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($authors as $index => $author): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($author['name']) ?></td>
                    <td><?= $author['book_count'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>