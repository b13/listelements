# Changelog 

This extension is still in beta. While we are using it in testing and production environments, there are still things
that might change as feedback and experiences from real-life use cases come in. Here's a list of recent breaking
changes.

## Version 0.2.0

### Renaming of database tables and fields

Database fields and tables have been renamed to comply with 
[TYPO3 best practices](https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/NamingConventions/Index.html#database-table-name).

* The default database field for holding a list for content elements has been renamed to `tx_listelements_list`.
* The database table for list items has been renamed to `tx_listelements_item`.
* LLL-keys have updated identifiers according to new field names/table names.

After updating, make sure to update your project files as follows:

#### Update database

*After* adding new fields and tables to your database using the install tool/TYPO3 console, prepare a database backup 
just in case. Assuming the table `tx_listelements_item` is empty, run the following commands:

```
DROP TABLE tx_listelements_item;
ALTER TABLE listitems RENAME TO tx_listelements_item;

UPDATE tt_content SET tx_listelements_list = list WHERE list != '';
UPDATE tx_listelements_item SET fieldname = 'tx_listelements_list' WHERE fieldname = 'list';
UPDATE sys_file_reference SET tablenames = 'tx_listelements_item' WHERE tablenames = 'listitems';
```

#### Update your TCA of custom elements created

* Update all references for content elements using the old field `list` to now use `tx_listelements_list`. This also 
  applies to palettes created.
* Update custom fields added to the table `listelements` to now be added to `tx_listelements_list`.
* Update references to LLL labels taken from `EXT:listelements` (if you re-used labels in your TCA/PageTSConfig).
* Update all references to field `image` of listelements, this field has been renamed to `images` to be more precise 
  (and in line with `assets`).
* Update backend preview templates (if in use) if certain fields or provided data has been used (`image`, `allImage`-Arrays)
