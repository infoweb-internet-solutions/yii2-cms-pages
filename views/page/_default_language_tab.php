<?php
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use infoweb\pages\models\Page;
?>
<div class="tab-content language-tab">
    
    <h3 class="page-header"><?php echo Yii::t('infoweb/pages', 'Page'); ?></h3>
    
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "PageLang[{$model->language}][name]",
        'data-slugable' => 'true',
        'data-slug-target' => "#aliaslang-{$model->language}-url",
        'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false
    ]); ?>
    
    <?= $form->field($alias, "[{$alias->language}]url")->textInput([
        'maxlength' => 255,
        'name' => "AliasLang[{$alias->language}][url]",
        //'placeholder' => '/'.$model->language.'/',
        'data-slugified' => 'true',
        'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "PageLang[{$model->language}][title]"
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'name' => "PageLang[{$model->language}][content]",
        'editorOptions' => ArrayHelper::merge(Yii::$app->getModule('cms')->getCKEditorOptions(), ['height' => 500]),
    ]); ?>
    
    <h3 class="page-header">SEO</h3>

    <?= $form->field($seo, "[{$seo->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "SeoLang[{$seo->language}][title]",
    ]); ?>
    
    <?= $form->field($seo, "[{$seo->language}]description")->textArea([
        'name' => "SeoLang[{$seo->language}][description]",
    ]); ?>
    
    <?= $form->field($seo, "[{$seo->language}]keywords")->textArea([
        'name' => "SeoLang[{$seo->language}][keywords]",
    ]); ?> 
</div>