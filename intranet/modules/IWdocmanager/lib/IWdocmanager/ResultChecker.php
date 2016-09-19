<?php

/**
 * // XTEC ************ FITXER AFEGIT - Internal callback class used to check permissions to each IWdocmanager item
*  // 2015.02.19 @author - Nacho Abejaro
 */
class IWdocmanager_ResultChecker
{
    protected $enablecategorization;
    protected $enablecategorybasedpermissions;

    function __construct($enablecategorization, $enablecategorybasedpermissions)
    {
        $this->enablecategorization = $enablecategorization;
        $this->enablecategorybasedpermissions = $enablecategorybasedpermissions;
    }
    // This method is called by DBUtil::selectObjectArrayFilter() for each and every search result.
    // A return value of true means "keep result" - false means "discard".
    function checkResult(&$item)
    {
        $ok = (SecurityUtil::checkPermission('IWdocmanager::', "$item[pn_cr_uid]::$item[documentId]", ACCESS_OVERVIEW));
        if ($this->enablecategorization && $this->enablecategorybasedpermissions) {
            //ObjectUtil::expandObjectWithCategories($item, 'IWdocmanager', 'sid');
        	ObjectUtil::expandObjectWithCategories($item, 'IWdocmanager', 'documentId');
            $ok = $ok && CategoryUtil::hasCategoryAccess($item['__CATEGORIES__'], 'IWdocmanager');
        }
        return $ok;
    }
}