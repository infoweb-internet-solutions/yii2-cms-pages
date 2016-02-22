<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => Yii::t('infoweb/pages', 'Page'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('infoweb/pages', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'                   => $model,
        'templates'               => $templates,
        'sliders'                 => $sliders,
        'allowContentDuplication' => $allowContentDuplication
    ]) ?>

</div>