<?php

namespace brikdigital\hubspot\migrations;

use Craft;
use craft\db\Migration;

/**
 * m231109_203743_hubspot_landing_pages migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->archiveTableIfExists('{{%hubspot_landing_pages}}');
        $this->createTable('{{%hubspot_landing_pages}}', [
            'id' => $this->primaryKey(),
            'hubspot_key' => $this->string(),
            'name' => $this->string(),
            'url' => $this->string(),
            'language' => $this->string(),
            'authorName' => $this->string(),
            'state' => $this->string(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'dateArchived' => $this->dateTime()->null(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('{{%hubspot_landing_pages}}');
        return true;
    }
}
