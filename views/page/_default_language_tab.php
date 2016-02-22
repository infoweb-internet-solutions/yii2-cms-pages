<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\pages\models\Page;
use infoweb\cms\helpers\LanguageHelper;
?>
<div class="tab-content language-tab">
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][name]",
        'data-slugable' => 'true',
        'data-slug-target' => "#alias-{$model->language}-url",
        'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false,
        'data-duplicateable' => var_export($allowContentDuplication,true)
    ]); ?>

    <?php // Url field ?>
    <?= $this->render('@infoweb/alias/views/behaviors/alias/_url', [
        'form' => $form,
        'model' => $model,
        'alias' => $alias,
        'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false,
        'duplicateable' => var_export($allowContentDuplication,true),
        'urlPrefix' => Yii::getAlias("@baseUrl/{$model->language}/")
    ]) ?>

    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][title]",
        'data-duplicateable' => var_export($allowContentDuplication,true)
    ]); ?>

    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "Lang[{$model->language}][content]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), Yii::$app->getModule('pages')->ckEditorOptions, (LanguageHelper::isRtl($model->language)) ? ['contentsLangDirection' => 'rtl'] : []),
        'options' => ['data-duplicateable' => var_export($allowContentDuplication,true)]
    ]); ?>
</div>