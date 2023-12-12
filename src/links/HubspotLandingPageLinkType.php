<?php

namespace brikdigital\hubspot\links;

use brikdigital\hubspot\records\LandingPageRecord;
use Craft;
use craft\helpers\Cp;
use verbb\hyper\base\Link;
use verbb\hyper\fieldlayoutelements\LinkField;
use verbb\hyper\fields\HyperField;

class HubspotLandingPageLinkType extends Link
{
    // Properties
    // =========================================================================

    public string|array|null $landingPages = '*';

    // Static Methods
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('hubspot', 'Hubspot Landing Page');
    }

    public function getSettingsConfig(): array
    {
        $values = parent::getSettingsConfig();
        $values['landingPages'] = $this->landingPages;

        return $values;
    }

    // Public Methods
    // =========================================================================

    public function getLinkUrl(): ?string
    {
        $landingPage = LandingPageRecord::findOne(['uid' => $this->linkValue]);
        if ($landingPage) {
            return $landingPage->url;
        }

        return null;
    }

    public function getLinkText(): ?string
    {
        return $this->linkText;
    }

    public function getSettingsHtml(): ?string
    {
        return null;
    }

    public function getInputHtml(LinkField $layoutField, HyperField $field): ?string
    {
        $variables = $this->getInputHtmlVariables($layoutField, $field);
        // echo "<pre>";
        // var_dump($this->linkValue); exit;
        return Cp::selectHtml([
            'id' => $this->getInputId(),
            'describedBy' => '',
            'name' => 'linkValue',
            'value' => $this->linkValue,
            'options' => $this->_getOptions(),
            'disabled' => false,
        ]);
    }

    private function _getOptions()
    {
        $options = [
            [
                'label' => Craft::t('brik-hubspot', 'Choose a Landing Page'),
                'value' => '',
                'disabled' => true,
            ]
        ];

        $landingPages = LandingPageRecord::find()->all();
        foreach ($landingPages as $page) {
            $options[] = [
                'label' => $page->name,
                'value' => $page->uid,
            ];
        }

        return $options;
    }
}
