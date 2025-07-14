<?php

namespace brikdigital\hubspot\fields;

use brikdigital\hubspot\records\LandingPageRecord;
use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\db\ElementQueryInterface;
use craft\enums\AttributeStatus;
use craft\fields\Dropdown;
use craft\helpers\Cp;
use craft\helpers\StringHelper;

/**
 * Hubspot Landing Page field type
 */
class HubspotLandingPageField extends Dropdown
{
    public function init(): void
    {
        $this->options = [
            [
                'label' => Craft::t('brik-hubspot', 'Choose a Landing Page'),
                'value' => '',
                'disabled' => true,
            ]
        ];

        foreach (LandingPageRecord::find()->all() as $page) {
            $this->options[] = [
                'label' => $page->name,
                'value' => $page->url,
            ];
        }
    }

    public static function displayName(): string
    {
        return Craft::t('brik-hubspot', 'Hubspot Landing Page');
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

    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
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

    public function getStatus(ElementInterface $element): ?array
    {
        if ($element->isFieldModified($this->handle)) {
            return [
                AttributeStatus::Modified,
                Craft::t('app', 'This field has been modified.'),
            ];
        }

        return null;
    }
}
