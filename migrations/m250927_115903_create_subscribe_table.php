<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscribe}}`.
 */
class m250927_115903_create_subscribe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscribe}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk-subscribe-author', '{{%subscribe}}', 'author_id', '{{%author}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscribe-author', '{{%subscribe}}');
        $this->dropTable('{{%subscribe}}');
    }
}
