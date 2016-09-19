<?php

class IWgroups_Installer extends Zikula_AbstractInstaller {

    public function Install() {
        //Set module vars
        $this->setVar('grupinici', '')
                ->setVar('confesb', '')
                ->setVar('confmou', '');

        return true;
    }

    /**
     * Delete the IWgroups module
     * @author Albert Pérez Monfort (aperezm@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function Uninstall() {
        //Delete module vars
        $this->delVar('grupinici')
                ->delVar('confesb')
                ->delVar('confmou');

        //Deletion successfull
        return true;
    }

    /**
     * Update the IWgroups module
     * @author Albert Pérez Monfort (aperezm@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function upgrade($oldversion) {
        switch ($oldversion) {
            
        }
        return true;
    }

}
