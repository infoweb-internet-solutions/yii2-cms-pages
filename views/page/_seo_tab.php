<?php
use yii\bootstrap\Tabs;
use kartik\icons\Icon;

$tabs = [];

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
    $tabs[] = [
        'label' => $languageName . ((Yii::$app->getModule('pages')->allowContentDuplication) ? Icon::show('exchange', ['class' => 'duplicateable-all-icon not-converted', 'data-language' => $languageId]) : ''),
        'content' => $this->render('_default_seo_tab', [
            'form'  => $form,
            'seo'   => ($model->isNewRecord) ? (new \infoweb\seo\models\Seo)->getTranslation($languageId) : $model->seo->getTranslation($languageId),
        ]),
        'active' => ($languageId == Yii::$app->language) ? true : false
    ];
}
?>
<div class="tab-content default-tab">
    <?= Tabs::widget(['items' => $tabs, 'encodeLabels' => false]); ?>
</div>