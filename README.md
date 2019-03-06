silverstripe-subsite-modeladmins
================================

Extensions for subsite support of ModelAdmins and DataObjects

## Requirements
Silverstripe 4. See 3.0 branch for earlier versions. This branch is a work in progress, and subsites is not yet stable on Silverstripe 4.

## Usage:

In your config.yml file, include the extensions against the ModelAdmin and DataObject you want to add subsite support to.
For example, if you want to add Subsite support to the RedirectedURLs module:

    SilverStripe\RedirectedURLs\Model\RedirectedURL:
      extensions:
        - Adrexia\SubsiteModelAdmins\SubsiteModelExtension
    SilverStripe\RedirectedURLs\Admin\RedirectedURLAdmin:
      extensions:
        - Adrexia\SubsiteModelAdmins\SubsiteAdminExtension
