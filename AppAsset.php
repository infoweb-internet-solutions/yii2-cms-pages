<?php

namespace infoweb\pages;

use yii\web\AssetBundle as AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/pages/assets/';
    public $css = [
    ];
    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}