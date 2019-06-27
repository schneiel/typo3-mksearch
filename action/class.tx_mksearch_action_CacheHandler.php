<?php

tx_rnbase::load('tx_rnbase_action_CacheHandlerDefault');
tx_rnbase::load('tx_rnbase_util_Strings');

/**
 * Detailseite eines beliebigen Datensatzes aus Momentan Lucene oder Solr.
 *
 * @author Michael Wagner <dev@dmk-ebusiness.de>
 */
class tx_mksearch_action_CacheHandler extends tx_rnbase_action_CacheHandlerDefault
{
    /**
     * Generate a key used to store data to cache.
     *
     * @return string
     */
    protected function generateKey()
    {
        $key = $this->getCacheKey();
        // Parameter cHash anhängen
        $key .= '_'.md5(serialize($this->getAllowedParameters()));

        return $key;
    }

    /**
     * Liefert alle erlaubten parameter,
     * welche zum erzeugen des CacheKeys verwendet werden.
     *
     * @return array
     */
    private function getAllowedParameters()
    {
        $params = array();
        $allowed = Tx_Rnbase_Utility_Strings::trimExplode(
            ',',
            $this->getConfigValue('params.allowed', ''),
            true
        );
        foreach ($allowed as $p) {
            $params[$p] = $this->getConfigurations()->get($p);
        }

        return $params;
    }
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/action/class.tx_mksearch_action_CacheHandler.php']) {
    include_once $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mksearch/action/class.tx_mksearch_action_CacheHandler.php'];
}
