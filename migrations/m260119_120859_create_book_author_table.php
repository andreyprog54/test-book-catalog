<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%books}}`
 * - `{{%authors}}`
 */
class m260119_120859_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-book_author-book_id}}',
            '{{%book_author}}',
            'book_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-book_author-book_id}}',
            '{{%book_author}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-book_author-author_id}}',
            '{{%book_author}}',
            'author_id'
        );

        // add foreign key for table `{{%authors}}`
        $this->addForeignKey(
            '{{%fk-book_author-author_id}}',
            '{{%book_author}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-book_author-book_id}}',
            '{{%book_author}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-book_author-book_id}}',
            '{{%book_author}}'
        );

        // drops foreign key for table `{{%authors}}`
        $this->dropForeignKey(
            '{{%fk-book_author-author_id}}',
            '{{%book_author}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-book_author-author_id}}',
            '{{%book_author}}'
        );

        $this->dropTable('{{%book_author}}');
    }
}
