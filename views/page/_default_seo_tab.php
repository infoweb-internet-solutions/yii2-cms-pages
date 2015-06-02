<div class="tab-content seo-tab">


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