<div class="tab-content seo-tab">
    <?= $form->field($seo, "[{$seo->language}]title")->textInput([
        'maxlength' => 255,
        'name' => "SeoLang[{$seo->language}][title]",
        'data-duplicateable' => Yii::$app->getModule('pages')->allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($seo, "[{$seo->language}]description")->textArea([
        'name' => "SeoLang[{$seo->language}][description]",
        'data-duplicateable' => Yii::$app->getModule('pages')->allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= $form->field($seo, "[{$seo->language}]keywords")->textArea([
        'name' => "SeoLang[{$seo->language}][keywords]",
        'data-duplicateable' => Yii::$app->getModule('pages')->allowContentDuplication ? 'true' : 'false'
    ]); ?>
</div>