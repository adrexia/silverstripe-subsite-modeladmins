<?php

namespace Adrexia\SubsiteModelAdmins;


use SilverStripe\ORM\DataExtension;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Subsites\State\SubsiteState;


/**
 * Supplies neccessary subsite filtering to a ModelAdmin, and enables cms menu item
 *
 * @package subsite-modeladmins
 */
class SubsiteAdminExtension extends DataExtension {

    public function updateEditForm($form){

        $gridField = $form->Fields()->fieldByName($this->sanitiseClassNameExtension($this->owner->modelClass));
        if(class_exists(Subsite::class) && singleton($this->owner->modelClass)->hasDatabaseField('SubsiteID')){
            $list = $gridField->getList()->filter(array('SubsiteID'=>SubsiteState::singleton()->getSubsiteId()));
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
