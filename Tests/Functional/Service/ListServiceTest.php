<?php

declare(strict_types=1);

namespace B13\Listelements\Tests\Functional\Service;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use B13\Listelements\Service\ListService;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\WorkspaceAspect;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ListServiceTest extends FunctionalTestCase
{
    protected BackendUserAuthentication $backendUser;

    /**
     * @var non-empty-string[]
     */
    protected array $coreExtensionsToLoad = ['core', 'workspaces'];

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'listelements',
    ];

    /**
     * @test
     */
    public function itemIsFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_element.csv');
        $listService = GeneralUtility::makeInstance(ListService::class);
        $row = $this->getContentRecordByUid(1);
        $row = $listService->resolveListitems($row);
        self::assertSame(1, count($row['listitems']));
        self::assertSame(1, $row['listitems-numberOfVisibleItems']);
        self::assertSame('listitem-header', $row['listitems'][0]['header']);
    }

    /**
     * @test
     */
    public function workspaceItemIsFoundInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_workspace_element.csv');
        $this->initBackendUserInWorkspace();
        $row = $this->getContentRecordByUid(1);
        $listService = GeneralUtility::makeInstance(ListService::class);
        $row = $listService->resolveListitems($row);
        self::assertSame(1, count($row['listitems']));
        self::assertSame(1, $row['listitems-numberOfVisibleItems']);
        self::assertSame('header-listelement-ws', $row['listitems'][0]['header']);
    }

    /**
     * @test
     */
    public function liveItemIsFoundInNonWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_workspace_element.csv');
        $row = $this->getContentRecordByUid(1);
        $listService = GeneralUtility::makeInstance(ListService::class);
        $row = $listService->resolveListitems($row);
        self::assertSame(1, count($row['listitems']));
        self::assertSame(1, $row['listitems-numberOfVisibleItems']);
        self::assertSame('listitem-header', $row['listitems'][0]['header']);
    }

    /**
     * @test
     */
    public function deletedWorkspaceItemsAreNotListedInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/deleted_workspace_element.csv');
        $this->initBackendUserInWorkspace();
        $row = $this->getContentRecordByUid(1);
        $listService = GeneralUtility::makeInstance(ListService::class);
        $row = $listService->resolveListitems($row);
        self::assertSame(0, count($row['listitems']));
    }

    /**
     * @test
     */
    public function workspaceItemsHasCorrectOrderInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/sorting_workspace_elements.csv');
        $this->initBackendUserInWorkspace();
        $row = $this->getContentRecordByUid(1);
        $listService = GeneralUtility::makeInstance(ListService::class);
        $row = $listService->resolveListitems($row);
        self::assertSame(2, count($row['listitems']));
        self::assertSame(2, $row['listitems-numberOfVisibleItems']);
        self::assertSame('first item', $row['listitems'][0]['header']);
        self::assertSame('header-listelement-ws', $row['listitems'][1]['header']);
    }

    protected function getContentRecordByUid(int $uid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        return $queryBuilder->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
            )
            ->execute()
            ->fetchAssociative();
    }

    protected function initBackendUserInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/be_users.csv');
        $backendUser = $this->setUpBackendUser(1);
        $backendUser->setWorkspace(1);
        $GLOBALS['BE_USER'] = $backendUser;
        $context = GeneralUtility::makeInstance(Context::class);
        $workspaceAspect = new WorkspaceAspect(1);
        $context->setAspect('workspace', $workspaceAspect);
    }
}
