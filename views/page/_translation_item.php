<?php

//se dosamigos\ckeditor\CKEditor;

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;

?>

<input type="hidden" name="<?php echo $language; ?>[PageLang][language]" value="<?php echo $language; ?>">

<?= $form->field($model, 'title')->textInput([
    'maxlength' => 255,
    'name' => "{$language}[PageLang][title]",
    'id' => "title-{$language}",
]); ?>

<?php /*
<?= $form->field($model, 'content')->widget(CKEditor::className(), [
    'options' => [
        'rows' => 20,
        'name' => "{$language}[PageLang][content]",
        'id' => "{$language}[PageLang][content]",
    ],
    'preset' => 'standard',
]); ?>
*/ ?>

<?php

echo $form->field($model, 'content')->widget(CKEditor::className(),[
    'options' => [
        'name' => "{$language}[PageLang][content]",
        'id' => "{$language}[PageLang][content]",
    ],
    'editorOptions' => [
        'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
        'inline' => false, //по умолчанию false
    ],
]); ?>


<br>
<h3 class="page-header">SEO</h3>

<?= $form->field($seo, 'title')->textInput([
    'maxlength' => 255,
    'name' => "{$language}[SeoLang][title]",
]); ?>

<?= $form->field($seo, 'description')->textArea([
    'maxlength' => 255,
    'name' => "{$language}[SeoLang][description]",
]); ?>




