<?php

namespace brikdigital\hubspot\models;

use Craft;
use craft\base\Model;

/**
 * Hubspot settings
 */
class Settings extends Model
{
    public $accessToken = '$HUBSPOT_ACCESS_TOKEN';
    public $clientSecret = '$HUBSPOT_CLIENT_SECRET';
}
