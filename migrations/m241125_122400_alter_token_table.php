<?php

declare(strict_types=1);

use yii\db;

class m241125_122400_alter_token_table extends db\Migration
{
    private const TABLE_NAME = 'token';
    private const COLUMN_NAME = 'type';

    public function up(): void
    {
        $this->alterColumn(
            static::TABLE_NAME,
            static::COLUMN_NAME,
            $this->string(64)->notNull()
        );
    }

    public function down(): void
    {
        $this->alterColumn(
            static::TABLE_NAME,
            static::COLUMN_NAME,
            $this->string(64)->notNull()
        );
    }
}
