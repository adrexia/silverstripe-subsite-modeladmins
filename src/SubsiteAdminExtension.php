<?php

namespace Adrexia\SubsiteModelAdmins;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\Subsites\State\SubsiteState;

/**
 * Supplies necessary subsite filtering to a ModelAdmin, and enables cms menu item
 *
 * @package subsite-modeladmins
 *
 * @extends Extension<\SilverStripe\Admin\ModelAdmin>
 */
class SubsiteAdminExtension extends Extension
{
    protected function updateEditForm(Form $form): void
    {
        $modelClass = $this->owner->getModelClass();
        /** @var \SilverStripe\Forms\GridField\GridField $gridField */
        $gridField = $form->Fields()->fieldByName($this->sanitiseClassNameExtension($modelClass));
        if (class_exists(Subsite::class) && singleton($modelClass)->hasDatabaseField('SubsiteID')) {
            $list = $gridField->getList()->filter(['SubsiteID' => SubsiteState::singleton()->getSubsiteId()]);
            $gridField->setList($list);
        }
    }

    /**
     * Sanitise a model class' name (original method not available to extension)
     */
    protected function sanitiseClassNameExtension(string $class): string
    {
        return str_replace('\\', '-', $class);
    }

    public function subsiteCMSShowInMenu(): bool
    {
        return true;
    }
}
