<?php

namespace brikdigital\hubspot\console\controllers;

use brikdigital\hubspot\Hubspot as Plugin;
use brikdigital\hubspot\records\LandingPageRecord;
use craft\console\Controller;
use craft\helpers\App;
use HubSpot\Factory;
use yii\console\ExitCode;

/**
 * Hubspot controller
 */
class HubspotController extends Controller
{
    /**
     * Hubspot Sync command
     */
    public function actionSync(): int
    {
        $this->_syncLandingPages();

        return ExitCode::OK;
    }

    private function _syncLandingPages(): void
    {
        $accessToken = App::parseEnv(Plugin::getInstance()->getSettings()->accessToken);
        $response = Factory::createWithAccessToken($accessToken)->apiRequest([
            'path' => '/cms/v3/pages/landing-pages?state__in=PUBLISHED_OR_SCHEDULED&sort=-createdAt',
        ]);

        $contents = $response->getBody()->getContents();

        foreach (json_decode($contents, false, flags: JSON_THROW_ON_ERROR)->results as $object) {
            echo "Landing Page: " . $object->language . " - " . $object->id . "\n";
            $this->_saveLandingPageByObject($object);
            foreach ($object->translations as $language => $translation) {
                echo "Translation: " . $language . " - " . $translation->id . "\n";
                $result = parse_url($object->url);
                $translation->url = $result['scheme'] . '://' . $result['host'] . '/' . $translation->slug;
                $translation->language = $language;
                $translation->authorName = $object->authorName;
                $translation->archivedAt = $object->archivedAt;
                $this->_saveLandingPageByObject($translation);
            }
            
        }
    }

    private function _saveLandingPageByObject($object): void
    {
        $landingPage = LandingPageRecord::findOne([
            'hubspot_key' => $object->id,
            'language' => $object->language ?? null
        ]);
        
        if ($landingPage === null) {
            $landingPage = new LandingPageRecord();
        }

        $landingPage->hubspot_key = (int)$object->id;
        $landingPage->name = $object->name;
        $landingPage->url = $object->url;
        $landingPage->language = $object->language ?? null;
        $landingPage->authorName = $object->authorName;
        $landingPage->state = $object->state;
        $landingPage->dateCreated = date("Y-m-d h:i:s", strtotime($object->createdAt));
        $landingPage->dateUpdated = date("Y-m-d h:i:s", strtotime($object->updatedAt));
        $landingPage->dateArchived = date("Y-m-d h:i:s", strtotime($object->archivedAt));

        $landingPage->save();
    }
}
