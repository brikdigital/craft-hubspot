<?php
namespace brikdigital\hubspot\variables;

use brikdigital\hubspot\Hubspot;
use brikdigital\hubspot\records\LandingPageRecord;

class HubspotVariable
{
    // Public Methods
    // =========================================================================

    public function getPlugin(): Hubspot
    {
        return Hubspot::$plugin;
    }

    public function getLandingPages()
    {
        $landingPages = LandingPageRecord::find()->all();
        return count($landingPages);
    }
}
