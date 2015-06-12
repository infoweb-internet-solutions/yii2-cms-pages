<?php

use yii\db\Schema;
use yii\db\Migration;

class m150420_075510_add_public_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pages}}', 'public', Schema::TYPE_BOOLEAN.' UNSIGNED NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->dropColumn('{{%pages}}', 'public');
    }
}
