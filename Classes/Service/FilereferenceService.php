<?php

declare(strict_types=1);

namespace B13\Listelements\Service;

/*
 * This file is part of TYPO3 CMS-extension listitems by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilereferenceService
{
    public static function resolveFilereferences($field, $table, $uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $return = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->orderBy('sorting_foreign')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $uid),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter($table)),
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field))
            )
            ->execute()
            ->fetchAll();
        foreach ($return as $key => $reference) {
            // add the database record for the original/referenced file
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_file');
            $queryBuilder->getRestrictions()
                ->removeAll()
                ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $originalFile = $queryBuilder
                ->select('*')
                ->from('sys_file')
                ->where(
                    $queryBuilder->expr()->eq('uid', $reference['uid_local'])
                )
                ->execute()
                ->fetch();
            if ($originalFile !== false) {
                $return[$key]['originalFile'] = $originalFile;
            }
            // add the database record for the original file's metadata
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('sys_file_metadata');
            $queryBuilder->getRestrictions()
                ->removeAll()
                ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $originalFileMetaData = $queryBuilder
                ->select('*')
                ->from('sys_file_metadata')
                ->where(
                    $queryBuilder->expr()->eq('uid', $reference['uid_local'])
                )
                ->execute()
                ->fetch();
            if ($originalFileMetaData !== false) {
                $return[$key]['originalFileMetaData'] = $originalFileMetaData;
            }
        }
        return $return;
    }
}
