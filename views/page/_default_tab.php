<?php
use yii\bootstrap\Tabs;

$tabs = [];

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
    $tabs[] = [
        'label' => $languageName,
        'content' => $this->render('_default_language_tab', [
            'model' => $model->getTranslation($languageId),
            'form'  => $form,
            'seo'   => ($model->isNewRecord) ? (new \infoweb\seo\models\Seo)->getTranslation($languageId) : $model->seo->getTranslation($languageId),
            'alias' => ($model->isNewRecord) ? (new \infoweb\alias\models\Alias)->getTranslation($languageId) : $model->alias->getTranslation($languageId),
            'page' => $model
        ]),
        'active' => ($languageId == Yii::$app->language) ? true : false
    ];
}
?>
<div class="tab-content default-tab">
    <?= Tabs::widget(['items' => $tabs]); ?>
</div>