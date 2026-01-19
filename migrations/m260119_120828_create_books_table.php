<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m260119_120828_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull(),
            'cover_image' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
