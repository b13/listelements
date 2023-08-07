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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendWorkspaceRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

class ListService implements SingletonInterface
{
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

        $workspaceId = GeneralUtility::makeInstance(Context::class)->getAspect('workspace')->getId();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_listelements_item');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, $workspaceId));
        $queryBuilder
            ->select('*')
            ->from('tx_listelements_item')
            ->orderBy('sorting_foreign')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_foreign',
                    $queryBuilder->createNamedParameter($row['uid'], Connection::PARAM_INT)
                )
            );

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field)),
                $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table))
            );

        $items = $queryBuilder
            ->execute()
            ->fetchAllAssociative();

        $results = [];
        foreach ($items as $item) {
            BackendUtility::workspaceOL('tx_listelements_item', $item);
            if ($item !== false && !VersionState::cast($item['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER)) {
                $results[] = $item;
            }
        }
        if ($workspaceId > 0) {
            $results = $this->resortWorkspaceRows($results);
        }
        $row[$returnAs] = $results;

        // count the number of non-hidden list items in case we need this for a backend preview warning, error message etc.
        // saved to $row[$returnAs . '-numberOfVisibleItems']
        $queryBuilder->getRestrictions()
            ->add(GeneralUtility::makeInstance(HiddenRestriction::class));
        $queryBuilder->count('uid');

        $row[$returnAs . '-numberOfVisibleItems'] = $queryBuilder
            ->execute()
            ->fetchOne();

        $fileRepository = GeneralUtility::makeInstance(FileRepository::class);
        foreach ($row[$returnAs] as $key => $item) {
            foreach (explode(',', $filereferences) as $fieldname) {
                $fieldname = trim($fieldname);
                $row[$returnAs][$key]['processed' . ucfirst($fieldname)] = $fileRepository->findByRelation('tx_listelements_item', $fieldname, $item['uid']);
            }
        }
        return $row;
    }

    protected function resortWorkspaceRows(array $rows): array
    {
        $sortings = [];
        foreach ($rows as $row) {
            $sortings[] = $row['sorting_foreign'];
        }
        array_multisort($sortings, $rows, SORT_ASC, SORT_NUMERIC);
        return $rows;
    }

    public function resolveItemsForFrontend(int $uid): array
    {
        $workspaceId = GeneralUtility::makeInstance(Context::class)->getAspect('workspace')->getId();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_listelements_item');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        // do not use FrontendWorkspaceRestriction
        $queryBuilder->getRestrictions()
            ->removeByType(FrontendWorkspaceRestriction::class)
            ->add(GeneralUtility::makeInstance(WorkspaceRestriction::class, $workspaceId));
        $stm = $queryBuilder
            ->select('*')
            ->from('tx_listelements_item')
            ->orderBy('sorting_foreign')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_foreign',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter('tx_listelements_list')),
                $queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter('tt_content'))
            )
            ->execute();
        if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() === 10) {
            $rows = $stm->fetchAll();
        } else {
            $rows = $stm->fetchAllAssociative();
        }
        if ($workspaceId > 0) {
            $items = [];
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            foreach ($rows as $row) {
                $pageRepository->versionOL('tx_listelements_item', $row, true);
                if ($row !== false) {
                    $items[] = $row;
                }
            }
            $items = $this->resortWorkspaceRows($items);
            return $items;
        }
        return $rows;
    }
}
