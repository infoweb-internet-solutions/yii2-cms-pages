<?php

namespace infoweb\pages;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * Enable link between a page an a slider
     * @var boolean
     */
    public $enableSliders = false;
    
    public function init()
    {
        parent::init();

        Yii::configure($this, require(__DIR__ . '/config.php')); 
    }
}