<?php

namespace B13\Listelements\Hooks;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Backend\View\PageLayoutView;

/**
 * Class/Function to manipulate the rendering of item preview content
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
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        &$row
    ) {
        // get all list items including all assets
        if ($row['tx_listelements_list']) {
            \B13\Listelements\Service\ListService::resolveListitems($row);
        }
    }

}
