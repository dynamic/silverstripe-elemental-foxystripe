<?php

namespace Dynamic\Elements\FoxyStripe\Element;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\FoxyStripe\Page\ProductPage;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Versioned\GridFieldArchiveAction;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class ElementFeaturedProducts extends BaseElement
{
    /**
     * @var string
     */
    private static $singular_name = 'Featured Products Element';

    /**
     * @var string
     */
    private static $plural_name = 'Featured Products Elements';

    /**
     * @var array
     */
    private static $many_many = [
        'Products' => ProductPage::class,
    ];

    private static $many_many_extraFields = [
        'Products' => [
            'SortOrder' => 'Int',
        ]
    ];

    /**
     * @var string
     */
    private static $table_name = 'ElementFeaturedProducts';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'FileTracking',
            'LinkTracking',
        ]);

        if ($this->ID) {
            $products = $fields->dataFieldByName('Products');
            $fields->removeByName([
                'Products',
            ]);

            $config = $products->getConfig();
            $config->removeComponentsByType([
                GridFieldAddExistingAutocompleter::class,
                GridFieldAddNewButton::class,
                GridFieldArchiveAction::class,
            ]);
            $config->addComponent(new GridFieldOrderableRows('SortOrder'));
            $config->addComponent(new GridFieldAddExistingSearchButton());

            $fields->addFieldsToTab('Root.Main', [
                $products->setTitle('Featured Products'),
            ]);
        }

        return $fields;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Featured Products');
    }
}
