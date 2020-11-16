<?php

namespace B13\Listelements\Service;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ListService
{

    /**
     * @param array $row: The current data row for this item
     * @param string $field: Fieldname used to resolve the reference
     * @param string $table: Name of the table that holds the reference to this list items
     * @param string $filereferences: comma separated list of fields with file references
     *
     */
    public static function resolveListitems(array &$row, $field = '', $table = 'tt_content', $filereferences = 'assets')
    {
        $returnAs = $field ? 'listitems_' . $field : 'listitems';
        if ($returnAs === 'listitems_listelements') {
            $returnAs = 'listitems';
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('listitems');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, (int)$GLOBALS['BE_USER']->workspace));
        $queryBuilder
            ->select('*')
            ->from('listitems')
            ->orderBy('sorting_foreign')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $row['uid'])
            );

        if ($field) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field)),
                    $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table))
                );
        }

        $row[$returnAs] = $queryBuilder
            ->execute()
            ->fetchAll();

        // count the number of non-hidden list items in case we need this for a backend preview warning, error message etc.
        // saved to $row[$returnAs . '-numberOfVisibleItems']
                $queryBuilder->getRestrictions()
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));
        $queryBuilder->count('uid');

        $row[$returnAs . '-numberOfVisibleItems'] = $queryBuilder
            ->execute()
            ->fetchColumn(0);
        
        foreach($row[$returnAs] as $key => $item) {
            foreach(explode(',', $filereferences) as $fieldname) {
                $fieldname = trim($fieldname);
                if($item[$fieldname]) {
                    $row[$returnAs][$key]['all' . ucfirst($fieldname)] = \B13\Listelements\Service\FilereferenceService::resolveFilereferences(
                        $fieldname,
                        'listitems',
                        $item['uid']);
                }
            }
        }
    }

}
