<?php
use mihaildev\ckeditor\CKEditor;

$bootstrapAssetUrl = Yii::$app->assetManager->getAssetUrl(\yii\bootstrap\BootstrapAsset::register($this), 'css/bootstrap.css');
echo $bootstrapAssetUrl;
echo '<br>';
echo Yii::getAlias('@frontendUrl') . '/css/main.css';

?>
<div class="tab-content language-tab">
    
    <h3 class="page-header"><?php echo Yii::t('infoweb/pages', 'Page'); ?></h3>
    
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "PageLang[{$model->language}][name]",
        'data-slugable' => 'true',
        'data-slug-target' => "#aliaslang-{$model->language}-url"
    ]); ?>
    
    <?= $form->field($alias, "[{$alias->language}]url")->textInput([
        'maxlength' => 255,
        'name' => "AliasLang[{$alias->language}][url]",
        //'placeholder' => '/'.$model->language.'/',
        'data-slugified' => 'true'
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "PageLang[{$model->language}][title]"
    ]); ?>
    
    <?= $form->field($model, "[{$model->language}]content")->widget(CKEditor::className(), [
        'editorOptions' => [
            'height' => 300,
            'toolbarGroups' => Yii::$app->params['toolbarGroups'],
            'removeButtons' => Yii::$app->params['removeButtons'],
            'rows' => 20,
            'name' => "PageLang[{$model->language}][content]",
            'preset' => 'custom',
            'contentsCss' => [$bootstrapAssetUrl, Yii::getAlias('@frontendUrl') . '/css/main.css', Yii::getAlias('@frontendUrl') . '/css/editor.css'],
            'extraAllowedContent' => 'div(*)',
            'extraPlugins' => ['ckeditor-gwf-plugin', 'codemirror'],
            'font_names' => 'GoogleWebFonts',
            //'stylesSet ' => [],
        ],

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