<?php

class IWgroups_Api_User extends Zikula_AbstractApi {

    public function getall() {
        // Security check
        if (!SecurityUtil::checkPermission('IWgroups::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }
        $items = DBUtil::selectObjectArray('groups', '', '', '-1', '-1', 'gid');
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($items === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }
        // Return the items
        return $items;
    }

    /*
      Funció que recull la informació especifica d'un registre
     */

    public function get($args) {

        if (!isset($args['gid']) || !is_numeric($args['gid'])) {
            return LogUtil::registerError($this->__('Error! Could not do what you wanted. Please check your input.'));
        }

        $items = DBUtil::selectObjectByID('groups', $args['gid'], 'gid');

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($items === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }

        // Return the items
        return $items;
    }

    /*
      Funció que retorna els usuaris que no estan a cap grup
     */

    public function get_sense_grup() {

        $users = DBUtil::selectObjectArray('users', '', '', -1, -1, 'uid');
        // Check for a DB error
        if ($users === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }
        // get all users that are in any group
        $allUsersWithGroup = DBUtil::selectObjectArray('group_membership', '', '', -1, -1, 'uid');
        if ($allUsersWithGroup === false) {
            return LogUtil::registerError($this->__('Error! Could not load items.'));
        }
        $diff = array_diff_key($users, $allUsersWithGroup);
        $usersList = '$$';
        $registres = array();
        if (count($diff) > 0) {
            foreach ($diff as $user) {
                $usersList .= $user['uid'] . '$$';
            }
            //get all users information
            $sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
            $usersInfo = ModUtil::func('IWmain', 'user', 'getAllUsersInfo', array('sv' => $sv,
                        'list' => $usersList,
                        'info' => 'ccn'));
            foreach ($usersInfo as $key => $value) {
                $registres[] = array('name' => $value,
                    'id' => $key);
            }
        }
        return $registres;
    }

}
