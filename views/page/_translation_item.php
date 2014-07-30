<?php

use dosamigos\ckeditor\CKEditor;

?>

<div class="form-group">&nbsp;</div>

<input type="hidden" name="<?php echo $language; ?>[PageLang][language]" value="<?php echo $language; ?>">

<?= $form->field($model, 'title')->textInput([
    'maxlength' => 255,
    'name' => "{$language}[PageLang][title]",
    'id' => "title-{$language}",
]); ?>

<?= $form->field($model, 'content')->widget(CKEditor::className(), [
    'options' => [
        'rows' => 20,
        'name' => "{$language}[PageLang][content]",
        'id' => "{$language}[PageLang][content]",
    ],
    'preset' => 'standard',
]); ?>