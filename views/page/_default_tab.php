<?php
use yii\bootstrap\Tabs;
use kartik\icons\Icon;
use infoweb\cms\helpers\LanguageHelper;

$tabs = [];

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {

    $tabs[$languageId] = [
        'label' => $languageName . (($allowContentDuplication) ? Icon::show('exchange', ['class' => 'duplicateable-all-icon not-converted', 'data-language' => $languageId]) : ''),
        'content' => $this->render('_default_language_tab', [
            'model'                   => $model->getTranslation($languageId),
            'alias'                   => $model->getTranslation($languageId)->alias,
            'form'                    => $form,
            'page'                    => $model,
            'allowContentDuplication' => $allowContentDuplication
        ]),
        'active' => ($languageId == Yii::$app->language) ? true : false,
        'options' => ['class' => (LanguageHelper::isRtl($languageId)) ? 'rtl' : ''],
    ];
}
?>
<div class="tab-content default-tab">
    <?= Tabs::widget(['items' => $tabs, 'encodeLabels' => false]); ?>
</div>