<?php

namespace app\controllers;

use app\models\Books;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view'], // Public actions
                            'roles' => ['?', '@'], // Both guests and authenticated users
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create', 'update', 'delete'], // Protected actions
                            'roles' => ['@'], // Only authenticated users
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        if (Yii::$app->user->isGuest) {
                            return Yii::$app->getResponse()->redirect(['site/login']);
                        }
                        throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page.');
                    }
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Books models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Books::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Books();
        $authors = \app\models\Authors::getList();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

                if ($model->validate()) {
                    if ($model->coverFile) {
                        $fileName = uniqid('cover_', true) . '.' . $model->coverFile->extension;
                        $uploadPath = Yii::getAlias('@webroot/uploads/') . $fileName;

                        if ($model->coverFile->saveAs($uploadPath)) {
                            $model->cover_image = '/uploads/' . $fileName;
                        }
                    }

                    if ($model->save(false)) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $authors = \app\models\Authors::getList();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $oldCover = $model->cover_image;
            $model->coverFile = UploadedFile::getInstance($model, 'coverFile');

            if ($model->validate()) {
                if ($model->coverFile) {
                    $fileName = uniqid('cover_', true) . '.' . $model->coverFile->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/') . $fileName;

                    if ($model->coverFile->saveAs($uploadPath)) {
                        $model->cover_image = '/uploads/' . $fileName;

                        if ($oldCover) {
                            $oldPath = Yii::getAlias('@webroot') . $oldCover;
                            if (is_file($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                    }
                }

                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
