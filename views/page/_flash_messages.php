<?php if (Yii::$app->getSession()->hasFlash('page')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('page') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('page-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('page-error') ?></p>
</div>
<?php endif; ?>