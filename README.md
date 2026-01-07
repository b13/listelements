# EXT:listelements #

## About this extension

This extension adds list items to tt_content. It adds a database field `tx_listelements_list` to `tt_content` that 
allows adding flexible list items as IRRE records to any content element.

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

This extension adds a PageContentPreviewRendering Listener to resolve ListItems (and if needed further Relations to asses/images) to allow customized display using Fluid templates for the backend Page
Layout View. 

For TYPO3 Version > 12 the Listener is not required anymore, because TYPO3 use the Record Api to resolve relations automatically.

For TYPO3 Version > 13 the Listener is not used anymore (because the Event changed)

s. https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-92434-UseRecordAPIInPageModulePreviewRendering.html

Migrate or BE-Templates

old:

```html
<ul>
	<f:for each="{listitems}" as="item">
		<li>
			{item.header}
			<f:if condition="{item.processedImages}">
				<f:for each="{item.processedImages}" as="image">
					<f:image src="{image.uid}" treatIdAsReference="true"/>
				</f:for>
			</f:if>
		</li>
	</f:for>
</ul>
```

new:

```html
<ul>
    <f:for each="{record.tx_listelements_list}" as="item">
        <li>
            {item.header}
            <f:if condition="{item.images}">
                <f:for each="{item.images}" as="image">
                    <f:image src="{image.uid}" treatIdAsReference="true"/>
                </f:for>
            </f:if>
        </li>
    </f:for>
</ul>
```

## Important info on configuration

Remember to add the `hiddenpalette` to all `showitems`-configuration for your own content elements to have invisible
fields like the language uid saved for all list items.

