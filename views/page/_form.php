<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model infoweb\partials\models\PagePartial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => 'page-form',
        'options'                   => ['class' => 'tabbed-form'],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,       
    ]);

    // Initialize the tabs
    $tabs = [];
    
    // Add the language tabs
    foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
        $tabs[] = [
            'label' => $languageName,
            'content' => $this->render('_language_tab', [
                'model' => $model->getTranslation($languageId),
                'form'  => $form,
                'seo'   => ($model->isNewRecord) ? (new \infoweb\seo\models\Seo)->getTranslation($languageId) : $model->seo->getTranslation($languageId),
                'alias' => ($model->isNewRecord) ? (new \infoweb\alias\models\Alias)->getTranslation($languageId) : $model->alias->getTranslation($languageId),
                'page' => $model
            ]),
            'active' => ($languageId == Yii::$app->language) ? true : false
        ];
    } 
    
    // Add the default tab
    $tabs[] = [
        'label' => Yii::t('app', 'General'),
        'content' => $this->render('_default_tab', ['model' => $model, 'templates' => $templates, 'form' => $form]),
    ];
    
    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);   
    ?>
    
    <div class="form-group buttons">
        
        <?php // No referrer, default buttons ?>
        <?php if (Yii::$app->request->get('referrer') != 'menu-items') : ?>
            
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), ['class' => 'btn btn-default', 'name' => 'new']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['index'], ['class' => 'btn btn-danger']) ?>
        
        <?php // Referrer, custom buttons ?>
        <?php else : ?>
        
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), ['class' => 'btn btn-default', 'name' => 'close']) ?>
        <?= Html::a(Yii::t('app', 'Close'), ['/menu/menu-item/index'], ['class' => 'btn btn-danger']) ?>
            
        <?php endif; ?>     
    </div>

    <?php ActiveForm::end(); ?>

</div>