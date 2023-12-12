<?php

namespace brikdigital\hubspot\records;

use Craft;
use craft\db\ActiveRecord;
use yii\db\ActiveQueryInterface;

/**
 * Hubspot Landing Page record
 */
class LandingPageRecord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%hubspot_landing_pages}}';
    }
}
