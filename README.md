silverstripe-subsite-modeladmins
================================

Extensions for subsite support of ModelAdmins and DataObjects

Usage:

In your config.yml file, include the extensions against the ModelAdmin and DataObject you want to add subsite support to.
For example, if you want to add Subsite support to the taxonomy module:

	TaxonomyAdmin:
	  extensions:
	    - SubsiteAdminExtension
	TaxonomyTerm:
	  extensions:
	   - SubsiteModelExtension

