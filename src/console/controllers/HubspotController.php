<?php

namespace brikdigital\hubspot\console\controllers;

use brikdigital\hubspot\Hubspot as Plugin;
use brikdigital\hubspot\models\LandingPage;
use brikdigital\hubspot\records\LandingPageRecord;
use Craft;
use craft\console\Controller;
use yii\console\ExitCode;

/**
 * Hubspot controller
 */
class HubspotController extends Controller
{
    public $defaultAction = 'index';

    public function options($actionID): array
    {
        $options = parent::options($actionID);
        switch ($actionID) {
            case 'index':
                // $options[] = '...';
                break;
        }
        return $options;
    }

    /**
     * Hubspot Sync command
     */
    public function actionSync(): int
    {
        $this->_syncLandingPages();

        return ExitCode::OK;
    }

    private function _syncLandingPages()
    {
        $accessToken = \craft\helpers\App::parseEnv(Plugin::getInstance()->getSettings()->accessToken);
        $hubspot = \HubSpot\Factory::createWithAccessToken($accessToken);
        $response = $hubspot->apiRequest([
            'path' => '/cms/v3/pages/landing-pages?state__in=PUBLISHED_OR_SCHEDULED',
        ]);

        $contents = $response->getBody()->getContents();
        $data = json_decode($contents);

        foreach ($data->results as $object) {
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

    private function _saveLandingPageByObject($object)
    {
        $landingPage = LandingPageRecord::findOne([
            'hubspot_key' => $object->id,
            'language' => isset($object->language) ? $object->language : null
        ]);
        
        if ($landingPage == null) {
            $landingPage = new LandingPageRecord();
        }

        $landingPage->hubspot_key = (int)$object->id;
        $landingPage->name = $object->name;
        $landingPage->url = $object->url;
        $landingPage->language = isset($object->language) ? $object->language : null;
        $landingPage->authorName = $object->authorName;
        $landingPage->state = $object->state;
        $landingPage->dateCreated = date("Y-m-d h:i:s", strtotime($object->createdAt));
        $landingPage->dateUpdated = date("Y-m-d h:i:s", strtotime($object->updatedAt));
        $landingPage->dateArchived = date("Y-m-d h:i:s", strtotime($object->archivedAt));

        $landingPage->save();
    }
}
