<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 das Medienkombinat
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

/**
 * Hooks for search engine Zend_Lucene.
 */
class tx_mksearch_hooks_EngineZendLucene
{
    /**
     * Hook for converting fields before actual indexing.
     *
     * This method has to do some additional work the Zend Analyzer can't do.
     *
     * @param array $params:
     *                       ['data']    => &associative array[tx_mksearch_interface_IndexerField]
     */
    public function convertFields($params)
    {
        // Do some converting...
        // @see tx_mksearch_service_engine_ZendLucene::indexNew() - hooks
    }

    /**
     * Manipulate one single search term.
     *
     * This method can be used to normalize search terms
     * to match conditions of indexed data, e. g. adapt charse encoding.
     *
     * @param array $params:
     *                       ['term']    => string
     */
    public function manipulateSingleTerm($params)
    {
        if (!isset($params['term'])) {
            throw new Exception('tx_mksearch_hooks_EngineZendLucene::manipulateSingleTerm(): No term given!');
        }
        // else
        $params['term'] = mb_strtolower($params['term'], 'utf-8');
    }
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/hooks/class.tx_mksearch_hooks_EngineZendLucene.php']) {
    include_once $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/hooks/class.tx_mksearch_hooks_EngineZendLucene.php'];
}
