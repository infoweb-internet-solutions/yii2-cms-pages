<?php

namespace infoweb\pages\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use infoweb\pages\models\Page;
use infoweb\pages\models\PageLang;
use infoweb\pages\models\PageTemplate;
use infoweb\pages\models\PageSearch;
use infoweb\seo\models\Seo;
use infoweb\alias\models\Alias;
use infoweb\alias\models\AliasLang;

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
                    'homepage' => ['post'],
                    'public' => ['post']
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
            'active' => 1,
            'homepage' => 0,
            'template_id' => 1,
            'public' => (int) $this->module->defaultPublicVisibility
        ]);

        // Get all the templates
        $templates = PageTemplate::find()->orderBy(['name' => SORT_ASC])->all();
        
        // Get the sliders
        if ($this->module->enableSliders) {
            $sliders = ArrayHelper::map(\infoweb\sliders\models\Slider::find()->select(['id', 'name'])->orderBy('name')->all(), 'id', 'name');
        } else {
            $sliders = [];
        }
        
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
                
                // Create an array of alias models
                $aliasModels = [];
                
                foreach ($languages as $languageId => $languageName) {
                    $aliasModels[$languageId] = new AliasLang(['language' => $languageId]);
                }
                
                // Populate the alias models
                Model::loadMultiple($aliasModels, $post);

                // Validate the model and translation and alias models
                $response = array_merge(
                    ActiveForm::validate($model),
                    ActiveForm::validateMultiple($translationModels),
                    ActiveForm::validateMultiple($aliasModels)
                );
                
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
                        'templates' => $templates,
                        'sliders' => $sliders,
                    ]);
                }
                
                // Create the seo tag
                $seo = new Seo([
                    'entity'    => Seo::TYPE_PAGE,
                    'entity_id' => $model->id
                ]);
                
                if (!$seo->save()) {
                    return $this->render('create', [
                        'model' => $model,
                        'templates' => $templates,
                        'sliders' => $sliders,
                    ]);    
                }
                
                // Create the alias
                $alias = new Alias([
                    'entity'    => Alias::ENTITY_PAGE,
                    'entity_id' => $model->id,
                    'type'      => $model->type
                ]);
                
                if (!$alias->save()) {
                    return $this->render('create', [
                        'model' => $model,
                        'templates' => $templates,
                        'sliders' => $sliders,
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
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }

                    // Save the seo tag translations
                    $data = $post['SeoLang'][$languageId];
                    
                    $seo                = $model->seo;
                    $seo->language      = $languageId;
                    $seo->title         = (!empty($data['title'])) ? $data['title'] : $post['PageLang'][$languageId]['title'];
                    $seo->description   = $data['description'];
                    $seo->keywords      = $data['keywords'];
                    
                    if (!$seo->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }
                    
                    // Save the alias translations
                    $data = $post['AliasLang'][$languageId];
                    
                    $alias              = $model->alias;
                    $alias->language    = $languageId;
                    $alias->url         = $data['url'];
                    
                    if (!$alias->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }                        
                }
                
                $transaction->commit();
                
                // Switch back to the main language
                $model->language = Yii::$app->language;
                
                // Set flash message
                Yii::$app->getSession()->setFlash('page', Yii::t('app', '"{item}" has been created', ['item' => $model->name]));
                
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
            'templates' => $templates,
            'sliders' => $sliders
        ]);
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
        
        // Get all the templates
        $templates = PageTemplate::find()->orderBy(['name' => SORT_ASC])->all();
        
        // Get the sliders
        if ($this->module->enableSliders) {
            $sliders = ArrayHelper::map(\infoweb\sliders\models\Slider::find()->select(['id', 'name'])->orderBy('name')->all(), 'id', 'name');
        } else {
            $sliders = [];
        }
        
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

                // Create an array of alias models
                $aliasModels = [];
                
                foreach ($languages as $languageId => $languageName) {
                    $aliasModels[$languageId] = $model->alias->getTranslation($languageId);
                }
                
                // Populate the alias models
                Model::loadMultiple($aliasModels, $post);

                // Validate the model and translation and alias models
                $response = array_merge(
                    ActiveForm::validate($model),
                    ActiveForm::validateMultiple($translationModels),
                    ActiveForm::validateMultiple($aliasModels)
                );
                
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
                        'templates' => $templates,
                        'sliders' => $sliders,
                    ]);
                }

                // Update alias type
                $alias              = $model->alias;
                $alias->type        = $model->type;

                if (!$alias->save()) {
                    return $this->render('update', [
                        'model' => $model,
                        'templates' => $templates,
                        'sliders' => $sliders,
                    ]);
                }
                
                // Save the translation models and seo tags
                foreach ($languages as $languageId => $languageName) {
                    
                    // Save the translation
                    $data = $post['PageLang'][$languageId];
                    
                    $model->language    = $languageId;
                    $model->name        = $data['name'];
                    $model->title       = $data['title'];
                    $model->content     = $data['content'];
                    
                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }
                    
                    // Save the seo tag translations
                    $data = $post['SeoLang'][$languageId];
                    
                    $seo                = $model->seo;
                    $seo->language      = $languageId;
                    $seo->title         = (!empty($data['title'])) ? $data['title'] : $post['PageLang'][$languageId]['title'];
                    $seo->description   = $data['description'];
                    $seo->keywords      = $data['keywords'];

                    if (!$seo->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }
                    
                    // Save the alias translations
                    $data = $post['AliasLang'][$languageId];
                    
                    $alias              = $model->alias;
                    $alias->language    = $languageId;
                    $alias->url         = $data['url'];

                    if (!$alias->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'templates' => $templates,
                            'sliders' => $sliders,
                        ]);    
                    }                     
                }
                
                $transaction->commit();
                
                // Switch back to the main language
                $model->language = Yii::$app->language;
                
                // Set flash message
                Yii::$app->getSession()->setFlash('page', Yii::t('app', '"{item}" has been updated', ['item' => $model->name]));
              
                // Take appropriate action based on the pushed button
                if (isset($post['close'])) {
                    
                    // No referrer
                    if (Yii::$app->request->get('referrer') != 'menu-items')
                        return $this->redirect(['index']);
                    else
                        return $this->redirect(['/menu/menu-item/index']);
                    
                } elseif (isset($post['new'])) {
                    return $this->redirect(['create']);
                } else {
                    return $this->redirect(['update', 'id' => $model->id]);
                }    
            }    
        }

        return $this->render('update', [
            'model' => $model,
            'templates' => $templates,
            'sliders' => $sliders
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
        $model = $this->findModel($id);
        
        try {                    
            // Only Superadmin can delete system pages
            if ($model->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin'))
                throw new \yii\base\Exception(Yii::t('app', 'You do not have the right permissions to delete this item'));
        
            $transaction = Yii::$app->db->beginTransaction();
            $model->delete();
            $transaction->commit();    
        } catch (\yii\base\Exception $e) {
            // Set flash message
            Yii::$app->getSession()->setFlash('page-error', $e->getMessage());
    
            return $this->redirect(['index']);        
        }        
        
        // Set flash message
        Yii::$app->getSession()->setFlash('page', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

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
     * Set as homepage
     * @param string $id
     * @return mixed
     */
    public function actionHomepage()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->homepage = 1;
        $model->active = 1;

        return $model->save();
    }
    
    /**
     * Set public state
     * @param string $id
     * @return mixed
     */
    public function actionPublic()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->public = ($model->public == 1) ? 0 : 1;

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
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }
}
