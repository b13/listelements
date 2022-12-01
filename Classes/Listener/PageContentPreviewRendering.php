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

class PageContentPreviewRendering
{
    protected ListService $listService;

    public function __construct(ListService $listService)
    {
        $this->listService = $listService;
    }

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        $record = $event->getRecord();
        if ($record['tx_listelements_list'] ?? false) {
            $record = $this->listService->resolveListitems($record);
            $event->setRecord($record);
        }
    }
}
