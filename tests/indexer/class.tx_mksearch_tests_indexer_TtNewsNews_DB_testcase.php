<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 DMK E-Business GmbH
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once tx_rnbase_util_Extensions::extPath('mksearch', 'lib/Apache/Solr/Document.php');

/**
 * Wir müssen in diesem Fall mit der DB testen da wir die pages
 * Tabelle benötigen.
 *
 * @author Hannes Bochmann <hannes.bochmann@dmk-ebusiness.de>
 * @author Michael Wagner <michael.wagner@dmk-ebusiness.de>
 * @license http://www.gnu.org/licenses/lgpl.html
 *          GNU Lesser General Public License, version 3 or later
 */
class tx_mksearch_tests_indexer_TtNewsNews_DB_testcase extends tx_mksearch_tests_DbTestcase
{
    /**
     * Constructs a test case with the given name.
     *
     * @param string $name     the name of a testcase
     * @param array  $data     ?
     * @param string $dataName ?
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->importExtensions[] = 'tt_news';
        $this->importDataSets[] = tx_mksearch_tests_Util::getFixturePath('db/tt_news_cat_mm.xml');
        $this->importDataSets[] = tx_mksearch_tests_Util::getFixturePath('db/tt_news_cat.xml');
    }

    /**
     * Prüft ob nur die Elemente indiziert werden, die im
     * angegebenen Seitenbaum liegen.
     */
    public function testPrepareSearchDataWithIncludeCategoriesOption()
    {
        /* @var $indexer tx_mksearch_indexer_TtNewsNews */
        $indexer = tx_rnbase::makeInstance('tx_mksearch_indexer_TtNewsNews');
        $options = array(
            'include.' => array(
                'categories.' => array(
                    0 => 1,
                ),
            ),
        );

        $result = array('uid' => 1);
        $indexDoc = $indexer->prepareSearchData(
            'tt_news',
            $result,
            tx_mksearch_tests_Util::getIndexerDocument($indexer),
            $options
        );
        self::assertNotNull($indexDoc, 'Das Element wurde nicht indziert!');

        $result = array('uid' => 2);
        $indexDoc = $indexer->prepareSearchData(
            'tt_news',
            $result,
            tx_mksearch_tests_Util::getIndexerDocument($indexer),
            $options
        );
        self::assertNull($indexDoc, 'Das Element wurde indziert!');
    }

    /**
     * Prüft ob nur die Elemente indiziert werden, die im
     * angegebenen Seitenbaum liegen.
     */
    public function testPrepareSearchDataWithExcludeCategoriesOption()
    {
        $indexer = tx_rnbase::makeInstance('tx_mksearch_indexer_TtNewsNews');
        $options = array(
            'exclude.' => array(
                'categories.' => array(
                    0 => 1,
                ),
            ),
        );

        $result = array('uid' => 1);
        $indexDoc = $indexer->prepareSearchData(
            'tt_news',
            $result,
            tx_mksearch_tests_Util::getIndexerDocument($indexer),
            $options
        );
        self::assertNull($indexDoc, 'Das Element wurde doch indziert!');

        $result = array('uid' => 2);
        $indexDoc = $indexer->prepareSearchData(
            'tt_news',
            $result,
            tx_mksearch_tests_Util::getIndexerDocument($indexer),
            $options
        );
        self::assertNotNull($indexDoc, 'Das Element wurde doch nicht indziert!');
    }

    public function testPrepareSearchDataWithSinglePid()
    {
        $indexer = tx_rnbase::makeInstance('tx_mksearch_indexer_TtNewsNews');
        $indexDoc = tx_mksearch_tests_Util::getIndexerDocument($indexer);
        $options = array(
            'addCategoryData' => 1,
            'defaultSinglePid' => 0,
        );

        $aResult = array('uid' => 3, 'title' => 'Testnews');
        $indexDoc = $indexer->prepareSearchData('tt_news', $aResult, $indexDoc, $options);
        self::assertTrue(is_object($indexDoc), 'Das Element wurde nicht indziert!');

        $aIndexData = $indexDoc->getData();
        self::assertArrayHasKey('categorySinglePid_i', $aIndexData, 'categorySinglePid_i ist nicht gesetzt!');
        self::assertEquals('334', $aIndexData['categorySinglePid_i']->getValue(), 'categorySinglePid_i ist falsch gesetzt!');
        self::assertEquals(array(2, 3, 1, 4), $aIndexData['categories_mi']->getValue(), 'categories_mi hat die falsche Reihenfolge!');
    }

    public function testPrepareSearchDataWithDefaultSinglePid()
    {
        $indexer = tx_rnbase::makeInstance('tx_mksearch_indexer_TtNewsNews');
        $indexDoc = tx_mksearch_tests_Util::getIndexerDocument($indexer);
        $options = array(
            'addCategoryData' => 1,
            'defaultSinglePid' => 50,
        );

        $aResult = array('uid' => 4, 'title' => 'Testnews');
        $indexDoc = $indexer->prepareSearchData('tt_news', $aResult, $indexDoc, $options);
        self::assertTrue(is_object($indexDoc), 'Das Element wurde nicht indziert!');

        $aIndexData = $indexDoc->getData();
        self::assertArrayHasKey('categorySinglePid_i', $aIndexData, 'categorySinglePid_i ist nicht gesetzt!');
        self::assertEquals('50', $aIndexData['categorySinglePid_i']->getValue(), 'categorySinglePid_i ist falsch gesetzt!');
    }

    /**
     * @group integration
     */
    public function testPrepareSearchDataWithIncludeOptionsOtherThanCategories()
    {
        /* @var $indexer tx_mksearch_indexer_TtNewsNews */
        $indexer = tx_rnbase::makeInstance('tx_mksearch_indexer_TtNewsNews');
        $options = array(
            'include.' => array(
                'somethingelse' => '1',
            ),
        );

        $result = array('uid' => 5);
        $indexDoc = $indexer->prepareSearchData(
            'tt_news',
            $result,
            tx_mksearch_tests_Util::getIndexerDocument($indexer),
            $options
        );
        self::assertNotNull($indexDoc, 'Das Element wurde nicht indziert!');
    }
}
if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/tests/indexer/class.tx_mksearch_tests_indexer_TtContent_testcase.php']) {
    include_once $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/tests/indexer/class.tx_mksearch_tests_indexer_TtContent_testcase.php'];
}
