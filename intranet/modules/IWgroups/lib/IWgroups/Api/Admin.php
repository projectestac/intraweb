<?php

class IWgroups_Api_Admin extends Zikula_AbstractApi {
    /*
      Funció que modifica un grup de la base de dades
     */

    public function update($args) {

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        if (!isset($args['gid']) || (!isset($args['name']) || $args['name'] == '')) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        $group = ModUtil::apiFunc('IWgroups', 'User', 'get', array('gid' => $args['gid']));
        if ($group == false) {
            return LogUtil::registerError($this->__('The group where the action had to be carried out hasn\'t been found'));
        }

        $items = array('name' => $args['name'],
            'description' => $args['description']);
        $table = DBUtil::getTables();
        $c = $table['groups_column'];
        $where = "$c[gid]=$args[gid]";
        if (!DBUTil::updateObject($items, 'groups', $where)) {
            return LogUtil::registerError($this->__('Error! Update attempt failed.'));
        }
        return true;
    }

    /*
      Funció que crea un nou grup
     */

    public function create($args) {
        // Argument opcional
        if (!isset($args['description'])) {
            $args['description'] = '';
        }

        //Comprova que el nom grup hagi arribat
        if ((!isset($args['name']) || $args['name'] == '')) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        $item = array('name' => $args['name'],
            'description' => $args['description']);

        if (!DBUtil::insertObject($item, 'groups', 'gid')) {
            return LogUtil::registerError($this->__('An error has occurred while creating the group'));
        }

        // Return the id of the newly created item to the calling process
        return $item['gid'];
    }
    
    /*
      Funció que esborra un grup, les dades del mateix i buida els seus membres
     */
    public function delete($args) {

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //Comprovem que el paràmetre identitat hagi arribat
        if (!isset($args['gid'])) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        $group = ModUtil::apiFunc('IWgroups', 'User', 'get', array('gid' => $args['gid']));
        if ($group == false) {
            return LogUtil::registerError($this->__('The group where the action had to be carried out hasn\'t been found'));
        }

        if (!DBUtil::deleteObjectByID('groups', $args['gid'], 'gid')) {
            return LogUtil::registerError($this->__('An error has occurred while trying to delete a record from the data base'));
        }
        
        //Esborrem els membres del grup
        if (!DBUtil::deleteObjectByID('group_membership', $args['gid'], 'gid')) {
            return LogUtil::registerError($this->__('An error has occurred while trying to delete a record from the data base'));
        }

        //Retornem true ja que el procés ha finalitzat amb èxit
        return true;
    }

    /*
      Funció que copia els usuari d'un grup en un altre
    */
    public function afegeix_membres($args) {
        //Comprovem que el paràmetre identitat hagi arribat
        if (!isset($args['gid'])) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //Esborrem el registre de la taula de membres dels grups
        foreach ($args['uid'] as $id) {
            //Comprovem si l'usuari és o no membre del grup. En cas de ja ser-ho no el tornarem a posar
            $es_membre = ModUtil::apiFunc('IWgroups', 'admin', 'es_membre', array('gid' => $args['gid'],
                'uid' => $id));
            if (!$es_membre) {
                $item = array('gid' => $args['gid'],
                    'uid' => $id);
                if (!DBUtil::insertObject($item, 'group_membership')) {
                    return LogUtil::registerError($this->__('Error! Creation attempt failed.'));
                }
            }
        }

        //Retornem true ja que el procés ha finalitzat amb éxit
        return true;
    }
    
    
     /*
      Funció que comprova si un usuari és membre o no d'un grup i retorna true o false
     */

    public function es_membre($args) {
        //Comprovem que el paràmetre identitat hagi arribat
        if (!isset($args['gid'])) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }
        $table = DBUtil::getTables();
        $c = $table['group_membership_column'];
        $where = "$c[gid] = $args[gid] AND $c[uid] = $args[uid]";
        $nombre = DBUtil::selectObjectCount('group_membership', $where);

        if ($nombre > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /*
      Funció que mou els membres d'un grup a un altre
    */

    public function mou_membres($args) {

        //Comprovem que el paràmetre identitat hagi arribat
        if ($args['gid'] == 0 || $args['gid1'] == 0) {
            return LogUtil::registerError($this->__('Incorrect action during the group change'));
        }

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }
        
        $table = DBUtil::getTables();
        $c = $table['group_membership_column'];

        //Esborrem el registre de la taula de membres dels grups
        foreach ($args['uid'] as $id) {
            //Comprovem si l'usuari és o no membre del grup. En cas de ja ser-ho no el tornarem a posar
            $es_membre = ModUtil::apiFunc('IWgroups', 'admin', 'es_membre', array('gid' => $args['gid1'],
                'uid' => $id));
            if (!$es_membre) {
                $items = array('gid' => $args['gid1']);
                $where = "$c[uid] = $id AND $c[gid] = $args[gid]";
                if (!DBUTil::updateObject($items, 'group_membership', $where)) {
                    return LogUtil::registerError($this->__('Error! Update attempt failed.'));
                }
            } else {
                $where = "$c[gid]=$args[gid] AND $c[uid]=$id";
                if (!DBUTil::deleteWhere('group_membership', $where)) {
                    return LogUtil::registerError($this->__('An error has occurred while trying to delete a record from the data base'));
                }
            }
        }

        //Retornem true ja que el procés ha finalitzat amb èxit
        return true;
    }

    /*
      Funció que esborra els membres seleccionats d'un grup
     */

    public function esborra_membres($args) {
        //Comprovem que el paràmetre identitat hagi arribat
        if (!isset($args['gid'])) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }
        $table = DBUtil::getTables();
        $c = $table['group_membership_column'];
        //Esborrem el registre de la taula de membres dels grups
        foreach ($args['uid'] as $id) {
            $where = "$c[gid]=$args[gid] AND $c[uid]=$id";
            if (!DBUTil::deleteWhere('group_membership', $where)) {
                return LogUtil::registerError($this->__('An error has occurred while trying to delete a record from the data base'));
            }
        }
        //Repetim el procés per al grup de la dreta
        foreach ($args['uid1'] as $id) {
            $where = "$c[gid]=$args[gid1] AND $c[uid]=$id";
            if (!DBUTil::deleteWhere('group_membership', $where)) {
                return LogUtil::registerError($this->__('An error has occurred while trying to delete a record from the data base'));
            }
        }
        //Retornem true ja que el procés ha finalitzat amb èxit
        return true;
    }
    public function getlinks() {
        $links = array();
        if (SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('IWgroups', 'admin', 'newItem'), 'text' => $this->__('Add a new group'), 'id' => 'iwgroups_newgroup', 'class' => 'z-icon-es-new');
            $links[] = array('url' => ModUtil::url('IWgroups', 'admin', 'main'), 'text' => $this->__('View the groups'), 'id' => 'iwgroups_main', 'class' => 'z-icon-es-view');
            $links[] = array('url' => ModUtil::url('IWgroups', 'admin', 'membres'), 'text' => $this->__('Administer the members of the groups '), 'id' => 'iwgroups_members', 'class' => 'z-icon-es-group');
            $links[] = array('url' => ModUtil::url('IWgroups', 'admin', 'configura'), 'text' => $this->__('Configure the module'), 'id' => 'iwgroups_config', 'class' => 'z-icon-es-config');
        }
        return $links;
    }

}
