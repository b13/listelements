<?php

declare(strict_types=1);

namespace B13\Listelements\DataProcessing;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Listelements\Service\ListService;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

#[Autoconfigure(public: true)]
class ListItemsDataProcessor implements DataProcessorInterface
{
    public function __construct(protected ListService $listService, protected ContentDataProcessor $contentDataProcessor)
    {
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }
        $data = $cObj->data;
        if ((int)($data['tx_listelements_list'] ?? 0) === 0) {
            return $processedData;
        }
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'listitems');
        $table = $cObj->stdWrapValue('table', $processorConfiguration, 'tt_content');
        $field = $cObj->stdWrapValue('field', $processorConfiguration, 'tx_listelements_list');
        $items = $this->listService->resolveItemsForFrontend((int)($data['_LOCALIZED_UID'] ?? $data['uid']), $table, $field);
        $request = $cObj->getRequest();
        $processedRecordVariables = [];
        foreach ($items as $key => $record) {
            $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $recordContentObjectRenderer->setRequest($request);
            $recordContentObjectRenderer->start($record, 'tx_listelements_item');
            $processedRecordVariables[$key] = ['data' => $record];
            $processedRecordVariables[$key] = $this->contentDataProcessor->process($recordContentObjectRenderer, $processorConfiguration, $processedRecordVariables[$key]);
        }
        $processedData[$targetVariableName] = $processedRecordVariables;
        return $processedData;
    }
}
