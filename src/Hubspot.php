<?php

namespace brikdigital\hubspot;

use Craft;
use brikdigital\hubspot\fields\HubspotLandingPageField;
use brikdigital\hubspot\links\HubspotLandingPageLinkType;
use brikdigital\hubspot\models\Settings;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use verbb\hyper\services\Links;
use yii\base\Event;

/**
 * Hubspot plugin
 *
 * @method static Hubspot getInstance()
 * @method Settings getSettings()
 * @author Brik <plugins@brik.digital>
 * @copyright Brik
 * @license https://craftcms.github.io/license/ Craft License
 */
class Hubspot extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function () {
            $this->attachEventHandlers();
            // ...
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('hubspot/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = HubspotLandingPageField::class;
        });
        
        Event::on(Links::class, Links::EVENT_REGISTER_LINK_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = HubspotLandingPageLinkType::class;
        });
    }
}
