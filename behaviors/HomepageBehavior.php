<?php
namespace infoweb\pages\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use infoweb\pages\models\Page;

class HomepageBehavior extends AttributeBehavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'checkValue',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'checkValue'
        ];
    }
    
    public function checkValue($event)
    {
        $homepage = $this->owner->homepage;
        
        // Not set as homepage
        if ($homepage == 0) {
            // If no other pages are set as homepage, the owner is automatically
            // set as homepage
            $homepageExists = (boolean) Page::find()->where(['homepage' => 1])->count();
            
            if (!$homepageExists) {
                $this->owner->homepage = 1;
                // The homepage is always a public page
                $this->owner->public = 1;
            }
        } else {
            // The flag for the current homepage has to be unset only if the 
            // 'homepage' attribute of the owner changed (meaning that the
            // owner was not already the current homepage)
            if (in_array('homepage', array_keys($this->owner->getDirtyAttributes()))) {
                $currentHomepage = Page::findOne(['homepage' => 1]);
                
                if ($currentHomepage) {
                    $currentHomepage->homepage = 0;
                    $currentHomepage->save();
                }
            }
            
            // The homepage is always a public page
            if ($this->owner->public != 1)
                $this->owner->public = 1;
        }
    }
}    