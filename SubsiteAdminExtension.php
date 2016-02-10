<?php

class SubsiteAdminExtension extends DataExtension {

	public function updateEditForm($form){
		
		$gridField = $form->Fields()->fieldByName($this->sanitiseClassNameExtension($this->owner->modelClass));
		if(class_exists('Subsite') && Object::has_extension($this->owner->modelClass, 'SubsiteModelExtension')){
			$list = $gridField->getList()->filter(array('SubsiteID'=>Subsite::currentSubsiteID()));
			$gridField->setList($list);
		}
	}

	/**
	 * Sanitise a model class' name (original method not avaliable to extension)
	 * @return string
	 */
	protected function sanitiseClassNameExtension($class) {
		return str_replace('\\', '-', $class);
	}

	public function subsiteCMSShowInMenu(){
		return true;
	}
}
