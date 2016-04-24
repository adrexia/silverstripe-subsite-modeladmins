<?php

/**
 * Supplies neccessary subsite fields to a DataObject and ensures SQL queries
 * for the object are properly limited to the current subsite.
 *
 * @package Subsite-modeladmins
 */
class SubsiteModelExtension extends DataExtension {

	private static $has_one = array(
		'Subsite' => 'Subsite'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('SubsiteID');
		if(class_exists('Subsite')){
			$fields->push($subsite = HiddenField::create('SubsiteID','SubsiteID', Subsite::currentSubsiteID()));
		}
	}

	public function onBeforeWrite() {
		if (!$this->owner->ID && !$this->owner->SubsiteID) {
			$this->owner->SubsiteID = Subsite::currentSubsiteID();
		}
	}

	/**
	 * Update any requests to limit the results to the current site
	 */
	public function augmentSQL(SQLQuery &$query) {

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

		$subsiteID = (int)Subsite::currentSubsiteID();

		$froms=$query->getFrom();
		$froms=array_keys($froms);
		$tableName = array_shift($froms);
		if ($tableName !== $this->owner->ClassName) {
			return;
		}
		$query->addWhere("\"$tableName\".\"SubsiteID\" IN ($subsiteID)");
	}
}
