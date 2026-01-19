<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author_subscriptions}}`.
 */
class m260119_214720_create_author_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%author_subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(11)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-author_subscription-author_id',
            '{{%author_subscription}}',
            'author_id'
        );

        $this->createIndex(
            'uq-author_subscription-author_phone',
            '{{%author_subscription}}',
            ['author_id', 'phone'],
            true
        );

        $this->addForeignKey(
            'fk-author_subscription-author_id',
            '{{%author_subscription}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-author_subscription-author_id',
            '{{%author_subscription}}'
        );

        $this->dropIndex(
            'uq-author_subscription-author_phone',
            '{{%author_subscription}}'
        );

        $this->dropIndex(
            'idx-author_subscription-author_id',
            '{{%author_subscription}}'
        );

        $this->dropTable('{{%author_subscription}}');
    }
}
