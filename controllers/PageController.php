<?php

namespace infoweb\pages\controllers;

use Yii;
use infoweb\pages\models\Page;
use infoweb\pages\models\PageLang;
use infoweb\pages\models\search\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
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
                    'active' => ['post'],
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        // Load database default values
        $model->loadDefaultValues();

        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
                        
            if (!$model->load($post)) {
                return $this->render('create', [
                    'model' => $model,
                    'templates' => [1 => 'Home', 2 => 'Nieuws', 3 => 'Contact'],
                ]);
            }

            if (!$model->save()) {
                echo 'Model not saved';
                exit();
            }

            foreach (Yii::$app->params['languages'] as $k => $v) {
                
                $modelLang = $model->getTranslation($k);

                // nl-BE already exists after saving the model
                if (!isset($modelLang)) {
                    $modelLang = new PageLang;
                }
                
                $modelLang->page_id = $model->id;
                $modelLang->load($post[$k]);
                // @todo Remove this
                $modelLang->content = $post[$k]['PageLang']['content'];
                $modelLang->language = $post[$k]['PageLang']['language'];
                
                if (!$modelLang->save()) {
                    echo 'Model lang not saved';
                    exit();
                }
            }

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
                'templates' => [1 => 'Home', 2 => 'Nieuws', 3 => 'Contact'],
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
        $model = $this->findModel($id);

        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
                    
            if (!$model->load($post) || !$model->save()) {
                return $this->render('update', [
                    'model' => $model,
                    'templates' => [1 => 'Home', 2 => 'Nieuws', 3 => 'Contact'],
                ]);
            }
            
            foreach (Yii::$app->params['languages'] as $k => $v) {
                $modelLang = $model->getTranslation($k);
                $modelLang->page_id = $model->id;
                $modelLang->load($post[$k]);
                // @todo Remove this
                $modelLang->content = $post[$k]['PageLang']['content'];
                
                if (!$modelLang->save()) {
                    echo 'Model lang not saved';
                    exit();
                }
            }

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
                'templates' => [1 => 'Home', 2 => 'Nieuws', 3 => 'Contact'],
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
    public function actionActive()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
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
