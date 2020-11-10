# EXT:listelements #

## About this extension

This extension adds list items to tt_content. It adds a database field `b13_list` to `tt_content` that allows adding
flexible list items as IRRE records to any content element.

## Installation

Add the extension to your project by installing and adding the TypoScript setup to your site-Extension setup:

```
@import 'EXT:listelements/Configuration/TypoScript/setup.typoscript'
```

This adds the configuration for adding the list items as an array `listitems` to the variables available for your 
content element's Fluid template, like this:

```
<f:for each="{listitems}" as="item">
    ...    
</f:for>
```

## Backend PageLayoutView preview

This extension adds a Hook and a Service class to allow customized display using Fluid templates for the backend Page
Layout View. Use this to add customized preview data if you add additional assets/image fields to be resolved for 
backend preview:

`EXT:site_extension/Classes/Hooks/DrawItem.php`:

```
<?php

namespace B13\SiteExtension\Hooks;

use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Backend\View\PageLayoutView;

/**
 * Class/Function which manipulates the rendering of item example content
 *
 */
class DrawItem implements PageLayoutViewDrawItemHookInterface
{

    /**
     * @param PageLayoutView $parentObject : The parent object that triggered this hook
     * @param boolean $drawItem : A switch to tell the parent object, if the item still must be drawn
     * @param string $headerContent : The content of the item header
     * @param string $itemContent : The content of the item itself
     * @param array $row : The current data row for this item
     *
     * @return void
     */
    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {

        // get all list items including all assets/assets2
        if ($row['test_list']) {
            // array &$row, $field = '', $table = 'tt_content', $filereferences = 'assets, additional_assets'
            \B13\Listelements\Service\ListService::resolveListitems($row, 'test_list', 'tt_content');
        }

    }

}
```

## Note on upgrading to version 2

Updating this extension from a v1 you will need to update your database and possibly adjust your TypoScript:

### Updating the database

Version 2 renames the field holding the uid of the parent record from `contentelement` to `uid_foreign` for possible
uses with other records beside `tt_content`. Install the extension and rename the table column or add the new 
field and update all records accordingly:

`UPDATE listitems SET uid_foreign = contentelement;`

### Adjusting your TypoScript

If your setup did make changes to the `dataProcessing` configuration provided with earlier versions of this extension
you might need to change the key in use (from `.21` to `.1421884800`). (This change reduces the risk of changing the
dataProcessing by mistake).
 
## Important info on configuration

Remember to add the `hiddenpalette` to all `showitems`-configuration for your own content elements to have invisible
fields like the language uid saved for all list items.

## Additional extensions

There are several extensions that enable additional options and fields:

### EXT:listelements_extended

Extends list functionality to additional fields, alloweing multiple lists for a single content element or adding list
items to other tables, like `pages`, by adding two fields `fieldname` and `tablename` to all list items.

### EXT:listelements_assets2

Adds a second asset/file field to all list items.

### EXT:listelements_publishdate

Adds starttime/endtime to all list items.

### EXT:listelements_bodytext_rte

Adds a second bodytext field configured for use with an RTE.

## Changelog

### v3.0

* Change dependency to EXT:b13_baseconfig

### v2.0

#### Breaking changes:

* Use `uid_foreign` to reference foreign record uid. This is a more consistent naming when using other record types.
* Change `dataProcessing` to key `.1421884800`


