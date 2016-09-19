<?php

class IWgroups_Controller_Admin extends Zikula_AbstractController {

    public function postInitialize() {
        $this->view->setCaching(false);
    }

    /**
     * Show the list of groups
     * @author:     Albert Pérez Monfort (aperezm@xtec.cat)
     * @return:	The list of groups
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //get all groups infortion
        $groups = ModUtil::apiFunc('IWgroups', 'user', 'getall');

        if (empty($groups)) {
            return LogUtil::registerError($this->__('There isn\'t any group defined'));
        }

        return $this->view->assign('groups', $groups)
                        ->fetch('IWgroups_admin_main.tpl');
    }

    /*
      Funció que presenta el formulari que ens mostra i permet editar les dades del
      grup que es vol modificar
     */

    public function edita($args) {
        $gid = FormUtil::getPassedValue('gid', isset($args['gid']) ? $args['gid'] : null, 'GET');
        $obid = FormUtil::getPassedValue('obid', isset($args['obid']) ? $args['obid'] : null, 'GET');

        if (!empty($obid)) {
            $gid = $obid;
        }

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        $group = ModUtil::apiFunc('IWgroups', 'user', 'get', array('gid' => $gid));
        if ($group == false) {
            LogUtil::registerError($this->__('The group where the action had to be carried out hasn\'t been found'));
            return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
        }

        return $this->view->assign('group', $group)
                        ->fetch('IWgroups_admin_edit.tpl');
    }

    /*
      Funció que comprova que les dades enviades des del formulari de modificació d'un
      grup s'ajusten al que ha de ser i envia l'ordre de crear el registre a la
      funció update de l'API
     */

    public function update($args) {
        $gid = FormUtil::getPassedValue('gid', isset($args['gid']) ? $args['gid'] : null, 'POST');
        $name = FormUtil::getPassedValue('name', isset($args['name']) ? $args['name'] : null, 'POST');
        $description = FormUtil::getPassedValue('description', isset($args['description']) ? $args['description'] : null, 'POST');
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        // Confirm authorisation code
        $this->checkCsrfToken();

        if ($name == '') {
            LogUtil::registerError($this->__('You didn\'t state the group name.'));
            return System::redirect(ModUtil::url('IWgroups', 'admin', 'edita'), array('gid' => $gid));
        }

        if (ModUtil::apiFunc('IWgroups', 'admin', 'update', array('gid' => $gid,
                    'name' => $name,
                    'description' => $description))) {
            LogUtil::registerStatus($this->__('Group updated'));
        }

        //Enviem l'usuari a la taula de grups
        return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
    }

    /*
      Funció que presenta el formulari des d'on es demanen la dades del nou grup que es vol crear
     */

    public function newItem() {
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        return $this->view->fetch('IWgroups_admin_new.tpl');
    }

    /*
      Funció que comprova que les dades enviades des del formulari de creació d'un
      nou grup s'ajusten al que ha de ser i envia l'ordre de crear el registre a la
      funció new de l'API
     */

    public function create($args) {
        $name = FormUtil::getPassedValue('name', isset($args['name']) ? $args['name'] : null, 'POST');
        $description = FormUtil::getPassedValue('description', isset($args['description']) ? $args['description'] : null, 'POST');

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        // Confirm authorisation code
        $this->checkCsrfToken();

        if ($name == '') {
            LogUtil::registerError($this->__('You didn\'t state the group name.'));
            return System::redirect(ModUtil::url('IWgroups', 'admin', 'new'));
        }

        //Es crida la funció API amb les dades extretes del formulari
        $lid = ModUtil::apifunc('IWgroups', 'admin', 'create', array('name' => $name,
                    'description' => $description));

        if ($lid != false) {
            //S'ha creat un nou grup correctament
            LogUtil::registerStatus($this->__('A group ha been created'));
        }

        return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
    }

    /*
      Funció que gestiona l'esborrament d'un grup i envia les dades a la
      funció API corresponent per fer l'ordre efectiva
     */

    public function delete($args) {
        $gid = FormUtil::getPassedValue('gid', isset($args['gid']) ? $args['gid'] : null, 'GETPOST');
        $confirmation = FormUtil::getPassedValue('confirmation', isset($args['confirmation']) ? $args['confirmation'] : null, 'POST');
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        $group = ModUtil::apiFunc('IWgroups', 'user', 'get', array('gid' => $gid));
        if ($group == false) {
            LogUtil::registerError($this->__('The group where the action had to be carried out hasn\'t been found'));
            return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
        }

        //Demanem confirmació per l'esborrament del registre, si no s'ha demanat abans
        if ($confirmation == null) {
            return $this->view->assign('group', $group)
                            ->fetch('IWgroups_admin_delete.tpl');
        }

        // Confirm authorisation code
        $this->checkCsrfToken();

        //Cridem la funció API que farà l'esborrament del registre
        if (ModUtil::apiFunc('IWgroups', 'admin', 'delete', array('gid' => $gid))) {
            //L'esborrament ha estat un èxit i ho notifiquem
            LogUtil::registerStatus($this->__('Group deleted'));
        }

        //Enviem a l'usuari a la taula de grups
        return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
    }

    /*
      Modificació de la configuració del mòdul de grups
     */

    public function configura() {
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //Agafem les dades de configuració
        $grupinici = ModUtil::getVar('IWgroups', 'grupinici');
        $confesb = ModUtil::getVar('IWgroups', 'confesb');
        $confmou = ModUtil::getVar('IWgroups', 'confmou');

        $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
        $groups = ModUtil::func('IWmain', 'user', 'getAllGroups', array('sv' => $sv));
        if (empty($groups)) {
            return $this->view->assign('noGroups', '1')
                            ->fetch('IWgroups_admin_conf.tpl');
        }
        return $this->view->assign('grupinici', $grupinici)
                        ->assign('confesb', $confesb)
                        ->assign('confmou', $confmou)
                        ->assign('groups', $groups)
                        ->fetch('IWgroups_admin_conf.tpl');
    }

    /*
      Canvi de la configuració del mòdul
     */

    public function conf_modifica($args) {
        $grupinici = FormUtil::getPassedValue('grupinici', isset($args['grupinici']) ? $args['grupinici'] : 0, 'POST');
        $confesb = FormUtil::getPassedValue('confesb', isset($args['confesb']) ? $args['confesb'] : 0, 'POST');
        $confmou = FormUtil::getPassedValue('confmou', isset($args['confmou']) ? $args['confmou'] : 0, 'POST');

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        // Confirm authorisation code
        $this->checkCsrfToken();

        //Establim les variables de configuració
        ModUtil::setVar('IWgroups', 'grupinici', $grupinici);
        ModUtil::setVar('IWgroups', 'confesb', $confesb);
        ModUtil::setVar('IWgroups', 'confmou', $confmou);
        LogUtil::registerStatus($this->__('Configuration updated'));
        return System::redirect(ModUtil::url('IWgroups', 'admin', 'main'));
    }

    /*
      Funció que carrega els membres d'un grup i permet iniciar-ne la gestió
     */

    public function membres($args) {
        $gid = FormUtil::getPassedValue('gid', isset($args['gid']) ? $args['gid'] : 0, 'GETPOST');
        $gid1 = FormUtil::getPassedValue('gid1', isset($args['gid1']) ? $args['gid1'] : 0, 'GETPOST');

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //Carreguem el grup inicial
        if ($gid == '' || $gid == 0) {
            $gid = ModUtil::getVar('IWgroups', 'grupinici');
        }

        if ($gid > 0) {
            $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
            $membres = ModUtil::func('IWmain', 'user', 'getMembersGroup', array('sv' => $sv,
                        'gid' => $gid,
                        'onlyId' => 1));
        } else {
            $membres = ModUtil::apiFunc('IWgroups', 'user', 'get_sense_grup');
        }

        $nomembres = 0;
        if (empty($membres)) {
            $nomembres = 1;
        }

        $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
        $groups = ModUtil::func('IWmain', 'user', 'getAllGroups', array('sv' => $sv,
                    'less' => $gid1,
                    'plus' => $this->__('Choose a group...')));

        $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
        $groups1 = ModUtil::func('IWmain', 'user', 'getAllGroups', array('sv' => $sv,
                    'less' => $gid,
                    'plus' => $this->__('Choose a group...')));

        if ($gid1 != '' && $gid1 != 0) {
            $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
            $membres1 = ModUtil::func('IWmain', 'user', 'getMembersGroup', array('sv' => $sv,
                        'gid' => $gid1,
                        'onlyId' => 1));
        } else {
            $membres1 = ModUtil::apiFunc('IWgroups', 'user', 'get_sense_grup');
        }

        asort($membres1);
        asort($membres);

        $membresList = array_merge($membres, $membres1);

        $usersList = '$$';

        foreach ($membresList as $member) {
            $usersList .= $member['id'] . '$$';
        }

        //get all users information
        $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
        $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                    'list' => $usersList,
                    'info' => 'ccn'));

        $nomembres1 = 0;
        if (empty($membres1)) {
            $nomembres1 = 1;
        }

        $nom_grup = array();
        $nom_grup1 = array();

        if ($gid > 0) {
            $nom_grup = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid));
        }

        if ($gid1 > 0) {
            $nom_grup1 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid1));
        }

        return $this->view->assign('nom_grup', $nom_grup)
                        ->assign('nom_grup1', $nom_grup1)
                        ->assign('groups', $groups)
                        ->assign('groups1', $groups1)
                        ->assign('gid', $gid)
                        ->assign('gid1', $gid1)
                        ->assign('membres', $membres)
                        ->assign('membres1', $membres1)
                        ->assign('nomembres', $nomembres)
                        ->assign('nomembres1', $nomembres1)
                        ->assign('usersInfo', $usersInfo)
                        ->fetch('IWgroups_admin_members.tpl');
    }

    /*
      Funció que recull les dades enviades per la funció membres i les gestiona
     */

    public function grups($args) {
        $gid = FormUtil::getPassedValue('gid', isset($args['gid']) ? $args['gid'] : 0, 'GETPOST');
        $gid1 = FormUtil::getPassedValue('gid1', isset($args['gid1']) ? $args['gid1'] : 0, 'GETPOST');
        $action = FormUtil::getPassedValue('action', isset($args['action']) ? $args['action'] : 0, 'GETPOST');
        $uid0 = FormUtil::getPassedValue('uid0', isset($args['uid0']) ? $args['uid0'] : array(), 'GETPOST');
        $uid1 = FormUtil::getPassedValue('uid1', isset($args['uid1']) ? $args['uid1'] : array(), 'GETPOST');
        $confirmation = FormUtil::getPassedValue('confirmation', isset($args['confirmation']) ? $args['confirmation'] : 0, 'POST');

        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        //Si els dos grup estan buits. Cancelem l'acció
        if (($gid == 0 || $gid == '') && ($gid1 == 0 || $gid1 == '')) {
            LogUtil::registerError($this->__('Incorrect action during the group change'));
            return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                'gid1' => $gid1)));
        }

        // Confirm authorisation code
        $this->checkCsrfToken();

        switch ($action) {
            case 1:
                if (empty($uid0)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                //Si el grup de destí és el buit. Cancel·lem l'acció
                if (($gid1 == 0 || $gid1 == '')) {
                    LogUtil::registerError($this->__('Incorrect action during the group change'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                //Si l'usuari prové del grup buit no es demana confirmació i simplement s'afegeix al grup de destí
                if ($gid == '' || $gid == 0) {
                    $afegit = ModUtil::apiFunc('IWgroups', 'admin', 'afegeix_membres', array('gid' => $gid1,
                                'uid' => $uid0));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                //Si no cal demanar confirmació segons la configuració posem $comfirmacio=true
                if (ModUtil::getVar('IWgroups', 'confmou') == 0) {
                    $confirmation = 1;
                }

                if ($confirmation == 0) {
                    $group1 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid));
                    $group2 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid1));

                    $usersList = '$$';

                    foreach ($uid0 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    foreach ($uid1 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    //get all users information
                    $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                    $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                                'list' => $usersList,
                                'info' => 'ccn'));
                    return $this->view->assign('usersInfo', $usersInfo)
                                    ->assign('group1', $group1)
                                    ->assign('group2', $group2)
                                    ->assign('uid0', $uid0)
                                    ->assign('action', $action)
                                    ->assign('gid', $gid)
                                    ->assign('gid1', $gid1)
                                    ->fetch('IWgroups_admin_grups.tpl');
                }

                if (ModUtil::apifunc('IWgroups', 'admin', 'mou_membres', array('gid' => $gid,
                            'uid' => $uid0,
                            'gid1' => $gid1))) {
                    //El deplaçament dels usuaris ha anat bé
                    LogUtil::registerStatus($this->__('The groups changes have been successfull'));
                }
                break;
            case 2:
                if (empty($uid0)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                //Si el grup de destí és el buit. Cancel·lem l'acció
                if (($gid1 == 0 || $gid1 == '')) {
                    LogUtil::registerError($this->__('Incorrect action during the group change'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                if (ModUtil::apifunc('IWgroups', 'admin', 'afegeix_membres', array('gid' => $gid1,
                            'uid' => $uid0))) {
                    LogUtil::registerStatus($this->__('Users added to group successfully'));
                }
                break;
            case 3:
                if (empty($uid1)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                //Si el grup de destí és el buit. Cancel·lem l'acció
                if (($gid == 0 || $gid == '')) {
                    LogUtil::registerError($this->__('Incorrect action during the group change'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                //Si l'usuari prové del grup buit no es demana confirmació i simplement s'afegeix al grup de destí
                if ($gid1 == '' || $gid1 == 0) {
                    $afegit = ModUtil::apiFunc('IWgroups', 'admin', 'afegeix_membres', array('gid' => $gid,
                                'uid' => $uid1));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                //Si no cal demanar confirmació segons la configuració posem $comfirmacio=true
                if (ModUtil::getVar('IWgroups', 'confmou') == 0) {
                    $confirmation = 1;
                }

                if ($confirmation == 0) {
                    $group1 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid1));
                    $group2 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid));

                    $usersList = '$$';

                    foreach ($uid0 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    foreach ($uid1 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    //get all users information
                    $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                    $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                                'list' => $usersList,
                                'info' => 'ncc'));
                    return $this->view->assign('usersInfo', $usersInfo)
                                    ->assign('group1', $group1)
                                    ->assign('group2', $group2)
                                    ->assign('uid1', $uid1)
                                    ->assign('action', $action)
                                    ->assign('gid', $gid)
                                    ->assign('gid1', $gid1)
                                    ->fetch('IWgroups_admin_grups.tpl');
                }

                //Cridem la funció API que farà l'esborrament del registre
                if (ModUtil::apifunc('IWgroups', 'admin', 'mou_membres', array('gid' => $gid1,
                            'uid' => $uid1,
                            'gid1' => $gid))) {
                    //El deplaçament dels usuaris ha anat bé
                    LogUtil::registerStatus($this->__('The groups changes have been successfull'));
                }
                break;
            case 4:
                if (empty($uid1)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                //Si el grup de destí és el buit. Cancel·lem l'acció
                if (($gid == 0 || $gid == '')) {
                    LogUtil::registerError($this->__('Incorrect action during the group change'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }
                if (ModUtil::apifunc('IWgroups', 'admin', 'afegeix_membres', array('gid' => $gid,
                            'uid' => $uid1))) {
                    LogUtil::registerStatus($this->__('Users added to group successfully'));
                }
                break;
            case 5:
                if (empty($uid0) && empty($uid1)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                //Si no cal demanar confirmació segons la configuració posem $comfirmacio=true
                if (ModUtil::getVar('IWgroups', 'confesb') == 0) {
                    $confirmation = 1;
                }
                if ($confirmation == 0) {
                    if ($gid > 0) {
                        $group1 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid));
                    } else {
                        $group1 = array();
                    }
                    if ($gid1 > 0) {
                        $group2 = ModUtil::apifunc('IWgroups', 'user', 'get', array('gid' => $gid1));
                    } else {
                        $group2 = array();
                    }

                    $usersList = '$$';

                    foreach ($uid0 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    foreach ($uid1 as $userId) {
                        $usersList .= $userId . '$$';
                    }

                    //get all users information
                    $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                    $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                                'list' => $usersList,
                                'info' => 'ncc'));
                    return $this->view->assign('usersInfo', $usersInfo)
                                    ->assign('group1', $group1)
                                    ->assign('group2', $group2)
                                    ->assign('uid1', $uid1)
                                    ->assign('uid0', $uid0)
                                    ->assign('action', $action)
                                    ->assign('gid', $gid)
                                    ->assign('gid1', $gid1)
                                    ->fetch('IWgroups_admin_grups.tpl');
                }
                //Cridem la funció API que farà l'esborrament del registre
                if (ModUtil::apifunc('IWgroups', 'admin', 'esborra_membres', array('uid' => $uid0,
                            'gid' => $gid,
                            'uid1' => $uid1,
                            'gid1' => $gid1))) {
                    //L'esborrament ha estat un éxit i ho notifiquem
                    LogUtil::registerStatus($this->__('Users removed from groups successfully'));
                }
                break;
            case 6:
                if (empty($uid0) && empty($uid1)) {
                    LogUtil::registerError($this->__('No user selected'));
                    return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                                        'gid1' => $gid1)));
                }

                if (!empty($uid0) && !empty($uid1)) {
                    $users = array_merge($uid0, $uid1);
                } elseif (!empty($uid0)) {
                    $users = $uid0;
                } else {
                    $users = $uid1;
                }

                $usersList = '$$';

                foreach ($users as $userId) {
                    $usersList .= $userId . '$$';
                }

                $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                            'list' => $usersList,
                            'info' => 'ccn'));

                foreach ($users as $userId) {
                    $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                    $quinsgrups[$userId] = ModUtil::func('IWmain', 'user', 'getAllUserGroups', array('sv' => $sv,
                                'uid' => $userId));
                }

                $sv1 = ModUtil::func('IWmain', 'user', 'genSecurityValue');
                $groups = ModUtil::func('IWmain', 'user', 'getAllGroupsInfo', array('sv' => $sv1));

                return $this->view->assign('usersInfo', $usersInfo)
                                ->assign('action', $action)
                                ->assign('users', $users)
                                ->assign('gid', $gid)
                                ->assign('gid1', $gid1)
                                ->assign('quinsgrups', $quinsgrups)
                                ->assign('groups', $groups)
                                ->fetch('IWgroups_admin_grups.tpl');
            case 0:
                if ($gid == '') {
                    $gid = 0;
                }
                if ($gid1 == '') {
                    $gid1 = 0;
                }
                break;
        }
        return System::redirect(ModUtil::url('IWgroups', 'admin', 'membres', array('gid' => $gid,
                            'gid1' => $gid1)));
    }

}
