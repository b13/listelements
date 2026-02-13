<?php

declare(strict_types=1);

namespace B13\Listelements\Listener;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Listelements\Service\ListService;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Information\Typo3Version;

#[AsEventListener(identifier: 'b13-listelements-page-content-preview-rendering')]
class PageContentPreviewRendering
{
    public function __construct(protected ListService $listService) {}

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ((new Typo3Version())->getMajorVersion() > 13) {
            return;
        }
        $record = $event->getRecord();
        if ($record['tx_listelements_list'] ?? false) {
            $record = $this->listService->resolveListitems($record);
            $event->setRecord($record);
        }
    }
}
