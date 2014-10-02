<?php

namespace infoweb\pages\controllers;

use Yii;
use infoweb\pages\models\Page;
use infoweb\pages\models\PageLang;
use infoweb\pages\models\PageTemplate;
use infoweb\pages\models\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Seo;
use yii\widgets\ActiveForm;
use yii\base\Model;

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
        $languages = Yii::$app->params['languages'];

        // Load the model with default values
        $model = new Page([
            'type' => 'user-defined',
            'active' => 1
        ]);
        
        // Load all the translations
        $model->loadTranslations(array_keys($languages));
        
        // Get all the templates
        $templates = PageTemplate::find()->all();
        
        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);
                
                // Create an array of translation models
                $translationModels = [];
                
                foreach ($languages as $languageId => $languageName) {
                    $translationModels[$languageId] = new PageLang(['language' => $languageId]);
                }
                
                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation models
                $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('create', [
                        'model' => $model,
                        'templates' => $templates
                    ]);
                } 
                
                // Save the translations
                foreach ($languages as $languageId => $languageName) {
                    
                    $data = $post['PageLang'][$languageId];
                    
                    // Set the translation language and attributes                    
                    $model->language    = $languageId;
                    $model->name        = $data['name'];
                    $model->title       = $data['title'];
                    $model->content     = $data['content'];
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('create', [
                            'model' => $model,
                            'templates' => $templates
                        ]);    
                    }                      
                }
                
                $transaction->commit();
                
                // Switch back to the main language
                $model->language = Yii::$app->language;
                
                // Set flash message
                Yii::$app->getSession()->setFlash('page', Yii::t('app', '{item} has been created', ['item' => $model->name]));
                
                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    return $this->redirect(['index']);
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }   
            }    
        }

        return $this->render('create', [
            'model' => $model,
            'templates' => $templates
        ]);
        
        
        
        
        
        
        
        
        
        
        
        
        /*$model = new Page();
        // Load database default values
        $model->loadDefaultValues();

        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();

            if (!$model->load($post)) {
                echo 'Model not loaded';
                exit();
            }

            if (!$model->save()) {
                echo 'Model not saved';
                exit();
            }

            // Create seo
            $seo = new Seo;
            $seo->entity = 'page';
            $seo->entity_id = $model->id;

            if (!$seo->save()) {
                echo 'Seo not saved';
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

                // Seo
                $seoLang = $seo->getTranslation($k);

                // nl-BE already exists after saving the model
                if (!isset($seoLang)) {
                    $seoLang = new SeoLang;
                }

                $seoLang->seo_id = $seo->id;
                $seoLang->language = $k;
                //$seoLang->load($post[$k]);
                $seoLang->title = $post[$k]['SeoLang']['title'];
                $seoLang->description = $post[$k]['SeoLang']['description'];
                //echo '<pre>'; print_r($seoLang); echo '</pre>'; exit();

                if (!$seoLang->save()) {
                    echo 'Seo lang not saved';
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
                'templates' => [1 => 'Home', 2 => 'Pagina', 3 => 'Nieuws', 4 => 'Contact'],
            ]);
        }*/
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $languages = Yii::$app->params['languages'];
        $model = $this->findModel($id);
        
        // Load all the translations
        $model->loadTranslations(array_keys($languages));
        
        // Get all the templates
        $templates = PageTemplate::find()->all();
        
        if (Yii::$app->request->getIsPost()) {
            
            $post = Yii::$app->request->post();
            
            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {
                               
                // Populate the model with the POST data
                $model->load($post);
                
                // Create an array of translation models
                $translationModels = [];
                
                foreach ($languages as $languageId => $languageName) {
                    $translationModels[$languageId] = $model->getTranslation($languageId);
                }
                
                // Populate the translation models
                Model::loadMultiple($translationModels, $post);

                // Validate the model and translation models
                $response = array_merge(ActiveForm::validate($model), ActiveForm::validateMultiple($translationModels));
                
                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            
            // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();                
                
                // Save the main model
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                        'templates' => $templates
                    ]);
                } 
                
                // Save the translation models
                foreach ($languages as $languageId => $languageName) {
                    
                    $data = $post['PageLang'][$languageId];
                    
                    $model->language    = $languageId;
                    $model->name        = $data['name'];
                    $model->title       = $data['title'];
                    $model->content     = $data['content'];
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates
                        ]);    
                    }                      
                }
                
                $transaction->commit();
                
                // Switch back to the main language
                $model->language = Yii::$app->language;
                
                // Set flash message
                Yii::$app->getSession()->setFlash('partial', Yii::t('app', '{item} has been updated', ['item' => $model->name]));
              
                return $this->redirect(['index']);    
            }    
        }

        return $this->render('update', [
            'model' => $model,
            'templates' => $templates
        ]);
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
