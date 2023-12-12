<?php

namespace brikdigital\hubspot\fields;

use brikdigital\hubspot\records\LandingPageRecord;
use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\elements\db\ElementQueryInterface;
use craft\fields\Dropdown;
use craft\helpers\Html;
use craft\helpers\Cp;
use craft\helpers\StringHelper;
use yii\db\Schema;

/**
 * Hubspot Landing Page field type
 */
class HubspotLandingPageField extends Dropdown
{
    public function init(): void
    {
        
        $this->options = [
            [
                'label' => Craft::t('hubspot', 'Choose a Landing Page'),
                'value' => '',
                'disabled' => true,
            ]
        ];

        $landingPages = LandingPageRecord::find()->all();
        foreach ($landingPages as $page) {
            $this->options[] = [
                'label' => $page->name,
                'value' => $page->url,
            ];
        }

    }

    public static function displayName(): string
    {
        return Craft::t('hubspot', 'Hubspot Landing Page');
    }

    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'value' => $this->value,
        ]);
    }

    public function getSettingsHtml(): ?string
    {
        return null;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null): string
    {
        return Cp::selectHtml([
            'id' => $this->getInputId(),
            'describedBy' => '',
            'name' => $this->handle,
            'value' => $value->value,
            'options' => $this->options,
            'disabled' => false,
        ]);
    }

    public function getElementValidationRules(): array
    {
        return [];
    }

    protected function searchKeywords(mixed $value, ElementInterface $element): string
    {
        return StringHelper::toString($value, ' ');
    }

    public function getElementConditionRuleType(): array|string|null
    {
        return null;
    }

    public function modifyElementsQuery(ElementQueryInterface $query, mixed $value): void
    {
        parent::modifyElementsQuery($query, $value);
    }

    public function getStatus(ElementInterface $element): ?array
    {
        if ($element->isFieldModified($this->handle)) {
            return [
                Element::ATTR_STATUS_MODIFIED,
                Craft::t('app', 'This field has been modified.'),
            ];
        }

        return null;
    }
}
