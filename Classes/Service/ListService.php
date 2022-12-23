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
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

class ListService
{

    /**
     * @param array $row: The current data row for this item
     * @param string $field: Fieldname used to resolve the reference
     * @param string $table: Name of the table that holds the reference to this list items
     * @param string $filereferences: comma separated list of fields with file references
     */
    public static function resolveListitems(array &$row, $field = 'tx_listelements_list', $table = 'tt_content', $filereferences = 'assets,images')
    {
        $returnAs = 'listitems_' . $field;
        if ($returnAs === 'listitems_tx_listelements_list') {
            $returnAs = 'listitems';
        }

        $workspace = 0;
        if (isset($GLOBALS['BE_USER']) && isset($GLOBALS['BE_USER']->workspace)) {
            $workspace = $GLOBALS['BE_USER']->workspace;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_listelements_item');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, $workspace));
        $queryBuilder
            ->select('*')
            ->from('tx_listelements_item')
            ->orderBy('sorting_foreign')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $row['uid'])
            );

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field)),
                $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table))
            );

        $row[$returnAs] = $queryBuilder
            ->execute()
            ->fetchAll();

        foreach ($row[$returnAs] as $key => $specificRow) {
            BackendUtility::workspaceOL('tx_listelements_item', $specificRow);
            if ($specificRow !== false && !VersionState::cast($specificRow['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER)) {
                $row[$returnAs][$key] = $specificRow;
            } else {
                unset($row[$returnAs][$key]);
            }
        }

        // count the number of non-hidden list items in case we need this for a backend preview warning, error message etc.
        // saved to $row[$returnAs . '-numberOfVisibleItems']
        $queryBuilder->getRestrictions()
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));
        $queryBuilder->count('uid');

        $row[$returnAs . '-numberOfVisibleItems'] = $queryBuilder
            ->execute()
            ->fetchColumn(0);

        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        foreach ($row[$returnAs] as $key => $item) {
            foreach (explode(',', $filereferences) as $fieldname) {
                $fieldname = trim($fieldname);
                $row[$returnAs][$key]['processed' . ucfirst($fieldname)] = $fileRepository->findByRelation('tx_listelements_item', $fieldname, $item['uid']);
            }
        }
    }
}
