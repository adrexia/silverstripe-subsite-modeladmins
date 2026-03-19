<?php

namespace Adrexia\SubsiteModelAdmins;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Subsites\State\SubsiteState;

/**
 * Supplies neccessary subsite fields to a DataObject and ensures SQL queries
 * for the object are properly limited to the current subsite.
 *
 * @package Subsite-modeladmins
 *
 * @extends Extension<\SilverStripe\ORM\DataObject>
 */
class SubsiteModelExtension extends Extension
{
    private static array $has_one = [
        'Subsite' => Subsite::class,
    ];

    protected function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName('SubsiteID');
        if (class_exists(Subsite::class)) {
            $fields->push(HiddenField::create('SubsiteID', 'SubsiteID', SubsiteState::singleton()->getSubsiteId()));
        }
    }

    protected function onBeforeWrite(): void
    {
        if (!$this->owner->ID && !$this->owner->SubsiteID) {
            $this->owner->SubsiteID = SubsiteState::singleton()->getSubsiteId();
        }
    }

    /**
     * Update any requests to limit the results to the current site
     */
    protected function augmentSQL(SQLSelect $query, ?DataQuery $dataQuery = null): void
    {
        if (Subsite::$disable_subsite_filter) {
            return;
        }

        // If you're querying by ID, ignore the sub-site
        if ($query->filtersOnID()) {
            return;
        }
        $regexp = '/^(.*\.)?("|`)?SubsiteID("|`)?\s?=/';
        foreach ($query->getWhereParameterised($parameters) as $predicate) {
            if (preg_match($regexp, $predicate)) {
                return;
            }
        }

        $subsiteID = (int)SubsiteState::singleton()->getSubsiteId();

        $froms = $query->getFrom();
        $froms = array_keys($froms);
        $tableName = array_shift($froms);
        if ($tableName == $this->owner->baseTable()) {
            $query->addWhere(sprintf('"%s"."SubsiteID" = %d', $tableName, $subsiteID));
        }
    }
}
