<?php

// XTEC ************ FITXER AFEGIT - Allows searching on IWdocmanager module
// 2015.02.19 @author - Nacho Abejaro

class IWdocmanager_Api_Search extends Zikula_AbstractApi
{
    /**
     * Search plugin info
     **/
    public function info()
    {
        return array('title' => 'IWdocmanager',
                'functions' => array('IWdocmanager' => 'search'));
    }

    /**
     * Search form component
     **/
    public function options($args)
    {
        if (SecurityUtil::checkPermission('IWdocmanager::', '::', ACCESS_READ)) {
            // Create output object - this object will store all of our output so that
            // we can return it easily when required
            $render = Zikula_View::getInstance('IWdocmanager');
            $render->assign('active', (isset($args['active']) && isset($args['active']['IWdocmanager'])) || (!isset($args['active'])));
            return $render->fetch('IWdocmanager_search_options.tpl');
        }

        return '';
    }

    /**
     * Search plugin main function
     **/
    public function search($args)
    {
        if (!SecurityUtil::checkPermission('IWdocmanager::', '::', ACCESS_READ)) {
            return true;
        }

        ModUtil::dbInfoLoad('Search');
        $tables = DBUtil::getTables();
        $iwDocManagerColumn = $tables['IWdocmanager_column'];

        $where = Search_Api_User::construct_where($args,
                array($iwDocManagerColumn['fileName'],
                $iwDocManagerColumn['documentName'],
                $iwDocManagerColumn['fileName']),
                $iwDocManagerColumn['documentLink']);

        // Only search in published articles that are currently visible

        $sessionId = session_id();

        ModUtil::loadApi('IWdocmanager', 'user');

        $permChecker = new IWdocmanager_ResultChecker($this->getVar('enablecategorization'), $this->getVar('enablecategorybasedpermissions'));
        $documents = DBUtil::selectObjectArrayFilter('IWdocmanager', $where, null, null, null, '', $permChecker, null);

        foreach ($documents as $document)
        {
        	$item = array(
                'title' => $document['documentName'],
                'module'  => 'IWdocmanager',
            	'extra' => $document['documentId'],
            	'created' => $document['cr_date'],
            	//'text' => $document['fileOriginalName'],
            	'text' => 'Feu clic en el nom del document per descarregar el seu contingut complet',
                'session' => $sessionId
            );

            $insertResult = DBUtil::insertObject($item, 'search_result');
            if (!$insertResult) {
                return LogUtil::registerError($this->__('Error! Could not load any document.'));
            }
        }

        return true;
    }

    /**
     * Do last minute access checking and assign URL to items
     */

    public function search_check($args)
    {
        $datarow = &$args['datarow'];
        $documentId = $datarow['extra'];
        $datarow['url'] = ModUtil::url('IWdocmanager', 'user', 'downloadDocument', array('documentId' => $documentId));

        return true;
    }
}