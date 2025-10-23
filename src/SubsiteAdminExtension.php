<?php

namespace Adrexia\SubsiteModelAdmins;

use SilverStripe\Core\Extension;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Subsites\State\SubsiteState;

/**
 * Supplies neccessary subsite filtering to a ModelAdmin, and enables cms menu item.
 */
class SubsiteAdminExtension extends Extension
{
    public function updateEditForm($form)
    {
        $gridField = $form->Fields()->fieldByName($this->sanitiseClassNameExtension($this->getOwner()->modelClass));
        if (class_exists(Subsite::class) && singleton($this->getOwner()->modelClass)->hasDatabaseField('SubsiteID')) {
            $list = $gridField->getList()->filter(['SubsiteID' => SubsiteState::singleton()->getSubsiteId()]);
            $gridField->setList($list);
        }
    }

    public function subsiteCMSShowInMenu()
    {
        return true;
    }

    /**
     * Sanitise a model class' name (original method not avaliable to extension).
     *
     * @param mixed $class
     *
     * @return string
     */
    protected function sanitiseClassNameExtension($class)
    {
        return str_replace('\\', '-', $class);
    }
}
