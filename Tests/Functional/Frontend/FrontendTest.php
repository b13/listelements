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

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FrontendTest extends FunctionalTestCase
{
    /**
     * @var non-empty-string[]
     */
    protected array $coreExtensionsToLoad = ['core', 'frontend', 'workspaces', 'fluid_styled_content'];

    /**
     * @var array<string, non-empty-string>
     */
    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/listelements/Build/sites' => 'typo3conf/sites',
    ];

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'listelements',
        'listelements_example',
    ];

    /**
     * @test
     */
    public function itemIsRendered(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_element.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup.typoscript'],
            ]
        );
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'));
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringContainsString('listitem-header', $body);
    }

    /**
     * @test
     */
    public function workspaceItemIsRenderedInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/be_users.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_workspace_element.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup.typoscript'],
            ]
        );
        $context = new InternalRequestContext();
        $context = $context->withWorkspaceId(1)->withBackendUserId(1);
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'), $context);
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringContainsString('header-listelement-ws', $body);
        self::assertStringNotContainsString('listitem-header', $body);
    }

    /**
     * @test
     */
    public function worspaceItemIsNotRenderedInNonWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/be_users.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/single_workspace_element.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup.typoscript'],
            ]
        );
        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'), $context);
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringNotContainsString('header-listelement-ws', $body);
        self::assertStringContainsString('listitem-header', $body);
    }

    /**
     * @test
     */
    public function workspaceItemsIsRenderedInCorrectOrderInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/be_users.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/sorting_workspace_elements.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup.typoscript'],
            ]
        );
        $context = new InternalRequestContext();
        $context = $context->withWorkspaceId(1)->withBackendUserId(1);
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'), $context);
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringContainsString('first item header-listelement-ws', $body);
    }

    /**
     * @test
     */
    public function deletedworkspaceItemIsNotRenderedInWorkspace(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/workspace.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/be_users.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixture/deleted_workspace_element.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup.typoscript'],
            ]
        );
        $context = new InternalRequestContext();
        $context = $context->withWorkspaceId(1)->withBackendUserId(1);
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'), $context);
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringNotContainsString('listitem-header', $body);
    }

    /**
     * @test
     */
    public function pageItemIsRendered(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixture/page.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'constants' => ['EXT:listelements_example/Configuration/TypoScript/constants.typoscript'],
                'setup' => ['EXT:listelements_example/Configuration/TypoScript/setup_page.typoscript'],
            ]
        );
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'));
        $body = (string)$response->getBody();
        $body = $this->prepareContent($body);
        self::assertStringContainsString('listitem-header', $body);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function prepareContent(string $string): string
    {
        $lines = explode("\n", $string);
        $notEmpty = [];
        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $notEmpty[] = trim($line);
            }
        }
        $content = implode('', $notEmpty);
        $content = preg_replace('/.*<div id="listelements-start"><\/div>(.*)<div id="listelements-end"><\/div>.*/', '$1', $content);
        return $content;
    }
}
