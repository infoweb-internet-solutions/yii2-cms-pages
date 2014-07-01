<?php

namespace infoweb\pages;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->params['foo'] = 'bar';
        // ...  other initialization code ...

        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}