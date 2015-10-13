<?php
use yii\bootstrap\Tabs;
use kartik\icons\Icon;

$tabs = [];

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
    $tabs[] = [
        'label' => $languageName . ((Yii::$app->getModule('pages')->allowContentDuplication) ? Icon::show('exchange', ['class' => 'duplicateable-all-icon not-converted', 'data-language' => $languageId]) : ''),
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
    <?= Tabs::widget(['items' => $tabs, 'encodeLabels' => false]); ?>
</div>