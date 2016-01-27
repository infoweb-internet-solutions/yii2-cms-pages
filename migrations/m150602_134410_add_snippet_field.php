<?php

use yii\db\Schema;
use yii\db\Migration;

class m150602_134410_add_snippet_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pages_lang}}', 'snippet', Schema::TYPE_TEXT.' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%pages_lang}}', 'snippet');  
    }
}
