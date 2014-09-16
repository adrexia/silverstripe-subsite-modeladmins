<?php
class SubsiteModelExtension extends DataExtension {

	private static $has_one = array(
		'Subsite' => 'Subsite'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('SubsiteID');
		if(class_exists('Subsite')){
			$fields->push(new HiddenField('SubsiteID','SubsiteID', Subsite::currentSubsiteID()));
		}
	}
}
