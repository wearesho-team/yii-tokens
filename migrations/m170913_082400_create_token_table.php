<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m170913_082400_create_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('token', [
            'id' => $this->primaryKey(),

            'recipient' => $this->string()->notNull(),
            'token' => $this->string()->notNull(),
            'type' => $this->string(24)->notNull(),

            'data' => $this->json(),

            'delivery_count' => $this->integer()->unsigned()->defaultValue(0),
            'verify_count' => $this->integer()->unsigned()->defaultValue(0),

            'created_at' => $this->timestamp()->notNull()->defaultExpression('current_timestamp'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('token');
    }
}
