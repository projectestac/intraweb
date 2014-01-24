<?php
/**
 * EZComments
 *
 * @copyright (C) EZComments Development Team
 * @link http://code.zikula.org/ezcomments
 * @version $Id: common.php 687 2010-05-12 12:36:49Z herr.vorragend $
 * @license See license.txt
 */

/**
 * modify a comment
 *
 * This is a standard function that is called whenever an administrator
 * wishes to modify a comment
 *
 * @author The EZComments Development Team
 * @param tid the id of the comment to be modified
 * @return string the modification page
 */
function ezc_modify($args)
{
    // get the type of function call: admin or user
    $type = FormUtil::getPassedValue('type', 'user');
    if (!in_array($type, array('user', 'admin'))) {
        $type = 'user';
    }

    // get our input
    $id = isset($args['id']) ? $args['id'] : FormUtil::getPassedValue('id', null, 'GETPOST');

    // Security check
    $securityCheck = pnModAPIFunc('EZComments', 'user', 'checkPermission',
                                  array('module'    => '',
                                        'objectid'  => '',
                                        'commentid' => $id,
                                        'level'     => ACCESS_EDIT));
    if (!$securityCheck) {
        $redirect = base64_decode(FormUtil::getPassedValue('redirect'));
        if (!isset($redirect)) {
            $redirect = pnGetHomepageURL();
        }
        return LogUtil::registerPermissionError($redirect);
    }

    // load edithandler class from file
    $class = "EZComments_{$type}_modifyhandler";
    Loader::requireOnce('modules/EZComments/pnincludes/'.strtolower($class).'.class.php');

    // Create pnForm output object
    $pnf = FormUtil::newpnForm('EZComments');

    // Return the output that has been generated by this function
    return $pnf->pnFormExecute("ezcomments_{$type}_modify.htm", new $class);
}
