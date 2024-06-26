<?php

declare(strict_types=1);

namespace B13\Listelements\Service;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\RelationHandler;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

class ListService implements SingletonInterface
{
    private const TABLE = 'tx_listelements_item';

    /**
     * @param array $row: The current data row for this item
     * @param string $field: Fieldname used to resolve the reference
     * @param string $table: Name of the table that holds the reference to this list items
     * @param string $filereferences: comma separated list of fields with file references
     * @return array $row: the extend row
     */
    public function resolveListitems(array $row, string $field = 'tx_listelements_list', string $table = 'tt_content', string $filereferences = 'assets,images'): array
    {
        $returnAs = 'listitems_' . $field;
        if ($returnAs === 'listitems_tx_listelements_list') {
            $returnAs = 'listitems';
        }
        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        $workspaceId = GeneralUtility::makeInstance(Context::class)->getAspect('workspace')->getId();
        $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
        $relationHandler->setWorkspaceId($workspaceId);
        $relationHandler->start(
            '',
            self::TABLE,
            '',
            $row['uid'],
            $table,
            BackendUtility::getTcaFieldConfiguration($table, $field)
        );
        $results = $relationHandler->getFromDB();
        $results = $results[self::TABLE] ?? [];
        $items = [];
        $visibleItems = 0;
        foreach ($relationHandler->tableArray[self::TABLE] ?? [] as $uid) {
            if (isset($results[$uid])) {
                $item = $results[$uid];
                BackendUtility::workspaceOL(self::TABLE, $item);
                if ($item !== false && !$this->isDeletePlaceHolder($item)) {
                    if ((int)$item['hidden'] === 0) {
                        $visibleItems++;
                    }
                    foreach (explode(',', $filereferences) as $fieldname) {
                        $item['processed' . ucfirst($fieldname)] = $fileRepository->findByRelation(self::TABLE, $fieldname, $item['uid']);
                    }
                    $items[] = $item;
                }
            }
        }
        $row[$returnAs] = $items;
        $row[$returnAs . '-numberOfVisibleItems'] = $visibleItems;
        return $row;
    }

    protected function isDeletePlaceHolder(array $item): bool
    {
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 13) {
            return VersionState::cast($item['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER);
        }
        return VersionState::tryFrom($item['t3ver_state'] ?? 0) === VersionState::DELETE_PLACEHOLDER;
    }

    public function resolveItemsForFrontend(int $uid, string $table = 'tt_content', $field = 'tx_listelements_list'): array
    {
        $workspaceId = GeneralUtility::makeInstance(Context::class)->getAspect('workspace')->getId();
        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $relationHandler = GeneralUtility::makeInstance(RelationHandler::class);
        $relationHandler->setWorkspaceId($workspaceId);
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 13) {
            $additionalWhere = $pageRepository->enableFields(self::TABLE);
        } else {
            $constraints = $pageRepository->getDefaultConstraints(self::TABLE);
            if ($constraints === []) {
                $additionalWhere = '';
            }
            $expressionBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($table)
                ->expr();
            $additionalWhere = ' AND ' . $expressionBuilder->and(...$constraints);
        }
        $relationHandler->additionalWhere[self::TABLE] = $additionalWhere;
        $relationHandler->start(
            '',
            self::TABLE,
            '',
            $uid,
            $table,
            BackendUtility::getTcaFieldConfiguration($table, $field)
        );
        $results = $relationHandler->getFromDB();
        $results = $results[self::TABLE] ?? [];
        $items = [];
        foreach ($relationHandler->tableArray[self::TABLE] ?? [] as $uid) {
            if (isset($results[$uid])) {
                $item = $results[$uid];
                $pageRepository->versionOL(self::TABLE, $item, true);
                if ($item !== false) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }
}
