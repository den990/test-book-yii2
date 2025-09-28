<?php

namespace app\controllers;

use app\models\forms\AuthorForm;
use app\models\searches\AuthorSearch;
use app\models\Subscribe;
use Yii;
use app\models\Author;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class AuthorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['view', 'index', 'top-authors'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['subscribe'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new AuthorForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionSubscribe($id)
    {
        $author = $this->findModel($id);
        $model = new Subscribe(['author_id' => $author->id]);

        if ($model->load(Yii::$app->request->post()) && $model->subscribe()) {
            return $this->redirect(['author/view', 'id' => $author->id]);
        }

        return $this->redirect(['author/view', 'id' => $author->id]);
    }

    public function actionTopAuthors($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $topAuthors = Author::topAuthorsByYear($year);

        return $this->render('top-authors', [
            'topAuthors' => $topAuthors,
            'year' => $year,
        ]);
    }


    protected function findModel($id) //TODO измени на форму
    {
        if (($model = AuthorForm::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested author does not exist.');
    }
}
