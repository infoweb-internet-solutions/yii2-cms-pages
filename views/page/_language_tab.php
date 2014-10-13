<?php
use mihaildev\ckeditor\CKEditor;
?>
<div class="tab-content language-tab">
    
    <h3 class="page-header"><?php echo Yii::t('app', 'Page'); ?></h3>
    
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
            'toolbarGroups' => [
                ['name' => 'clipboard', 'groups' => ['mode','undo', 'selection', 'clipboard','doctools']],
                ['name' => 'editing', 'groups' => ['tools']],
                ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
                ['name' => 'insert'],
                ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                ['name' => 'colors'],
                ['name' => 'links'],
                ['name' => 'others'],
            ],
            'removeButtons' => 'Smiley,Iframe',
            'rows' => 20,
            'name' => "PageLang[{$model->language}][content]",
            'preset' => 'custom',
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