<?php

namespace infoweb\pages\controllers;

use Yii;
use infoweb\pages\models\Page;
use infoweb\pages\models\search\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    //'active' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $post = \Yii::$app->request->post();

        $model = new Page();
        // Load database default values
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (isset($post['close'])) {
                return $this->redirect(['index']);
            } elseif (isset($post['new'])) {
                return $this->redirect(['create']);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $post = \Yii::$app->request->post();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (isset($post['close'])) {
                return $this->redirect(['index']);
            } elseif (isset($post['new'])) {
                return $this->redirect(['create']);
            } else {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
 * Set active state
 * @param string $id
 * @return mixed
 */
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        $model->active = ($model->active == 1) ? 0 : 1;

        return $model->save();
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
