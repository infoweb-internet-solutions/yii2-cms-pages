<?php
use mihaildev\ckeditor\CKEditor;
?>
<div class="tab-content language-tab">
    <?= $form->field($model, "[{$model->language}]name")->textInput([
        'maxlength' => 255,
        'name' => "PageLang[{$model->language}][name]"
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
    
    <?php /*
    <h3 class="page-header">SEO</h3>

    <?= $form->field($seo, 'title')->textInput([
        'maxlength' => 255,
        'name' => "{$language}[SeoLang][title]",
    ]); ?>
    
    <?= $form->field($seo, 'description')->textArea([
        'maxlength' => 255,
        'name' => "{$language}[SeoLang][description]",
    ]); ?> 
    */ ?>
</div>