<?php

class IWgroups_Version extends Zikula_AbstractVersion {

    public function getMetaData() {
        $meta = array();
        $meta['displayname'] = $this->__("IWgroups");
        $meta['description'] = $this->__("Allow the creation, edition and removing of user groups.");
        $meta['url'] = $this->__("IWgroups");
        $meta['version'] = '3.0.0';
        $meta['securityschema'] = array('IWgroups::' => '::');
        /*
          $meta['dependencies'] = array(array('modname' => 'IWmain',
          'minversion' => '3.0.0',
          'maxversion' => '',
          'status' => ModUtil::DEPENDENCY_REQUIRED));
         *
         */
        return $meta;
    }

}
