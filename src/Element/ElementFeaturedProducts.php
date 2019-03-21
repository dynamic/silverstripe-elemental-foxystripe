<?php

namespace Dynamic\Elements\FoxyStripe\Element;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\FoxyStripe\Page\ProductPage;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Versioned\GridFieldArchiveAction;
use SilverStripe\ORM\FieldType\DBField;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class ElementFeaturedProducts extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-cart';

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

    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'Products' => [
            'SortOrder' => 'Int',
        ]
    ];

    /**
     * @var string
     */
    private static $table_name = 'ElementFeaturedProducts';

    /**
     * Set to false to prevent an in-line edit form from showing in an elemental area. Instead the element will be
     * clickable and a GridFieldDetailForm will be used.
     *
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @return \SilverStripe\Forms\FieldList
     */
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
     * @return DBHTMLText
     */
    public function getSummary()
    {
        if ($this->Products()->count() == 1) {
            $label = ' product';
        } else {
            $label = ' products';
        }
        return DBField::create_field('HTMLText', $this->Products()->count() . ' ' . $label)->Summary(20);
    }

    /**
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Featured Products');
    }
}
