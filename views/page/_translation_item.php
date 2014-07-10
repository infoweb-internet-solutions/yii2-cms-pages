<?php

use dosamigos\ckeditor\CKEditor;

?>

<div class="form-group">&nbsp;</div>

<input type="hidden" name="<?php echo $language; ?>[PageLang][language]" value="<?php echo $language; ?>">

<div class="form-group field-pagelang-title has-success">
    <label for="pagelang-title" class="control-label">Title</label>
    <input type="text" name="<?php echo $language; ?>[PageLang][title]" class="form-control" id="pagelang-title" value="<?php echo $model->getTranslation($language)->title; ?>">
    <div class="help-block"></div>
</div>

<?= $form->field($model->getTranslation($language), 'content')->widget(CKEditor::className(), [
    'options' => ['rows' => 20, 'name' => "{$language}[PageLang][content]"],
    'preset' => 'standard'
]); ?>