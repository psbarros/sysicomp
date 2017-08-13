<?php

use yii\db\Migration;

class m170813_080228_alter_trancamento extends Migration
{
    public function safeUp()
    {
        $this->addColumn('j17_trancamentos', 'nomeResponsavel',
            $this->string(300)->after('dataTermino'));
    }

    public function safeDown()
    {
        echo "m170813_080228_alter_trancamento cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170813_080228_alter_trancamento cannot be reverted.\n";

        return false;
    }
    */
}
