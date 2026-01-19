<?php

namespace app\controllers;

use app\models\Authors;
use yii\helpers\VarDumper;

class ReportController extends \yii\web\Controller
{
    public function actionTop10authors($year = null)
    {
        $year = $year ? (int)$year : null;
        $top10authors = Authors::getTopAuthorsByYear($year, 10)->asArray()->all();

        return $this->render('top10authors', [
            'authors' => $top10authors,
            'year' => $year
        ]);
    }

}
