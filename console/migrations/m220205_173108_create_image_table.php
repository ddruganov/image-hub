<?php

use yii\db\Migration;

class m220205_173108_create_image_table extends Migration
{
    public function safeUp()
    {
        $this->execute('create schema image');
        $this->createTable('image.image', [
            'id' => $this->primaryKey(),
            'size' => $this->integer()->notNull(),
            'hash' => $this->string(strlen(hash('sha256', '')))->notNull(),
            'extension' => $this->string(5)->notNull(),
            'created_at' => $this->timestamp()->notNull()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('image.image');
        $this->execute('drop schema image');
    }
}
