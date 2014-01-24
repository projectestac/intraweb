<?php
/**
 * Zikula Application Framework
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 347 2009-11-11 08:20:06Z herr.vorragend $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Recommend_Us
 */

/**
 * the main user function
 *
 * @author Mark West
 * @return string HTML string
 */
function Recommend_Us_user_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('Recommend_Us::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $pnRender = pnRender::getInstance('Recommend_Us');

    // Return the output that has been generated by this function
    return $pnRender->fetch('recommendus_user_main.htm');
}

/**
 * Send form
 *
 * This function displays the form by which the user enters the details of who
 * to recommend this site to
 *
 * @author Mark West
 * @return string HTML string
 */
function Recommend_Us_user_view()
{
    // Security check
    if (!SecurityUtil::checkPermission('Recommend_Us::', '::', ACCESS_OVERVIEW)) {
        return LogUtil::registerPermissionError();
    }

    // get the object id and module of the item to be recommended
    $modname  = FormUtil::getPassedValue('modname', null, 'REQUEST');
    $objectid = (int)FormUtil::getPassedValue('objectid', null, 'REQUEST');

    // Create output object
    $pnRender = pnRender::getInstance('Recommend_Us');

    // load the module API and get the item
    if ($objectid && is_numeric($objectid)) {
        if (isset($modname) && pnModAvailable($modname)) {
            $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
            $pnRender->assign('modname', $modname);
            $pnRender->assign('objectid', $objectid);
            $item = pnModAPIFunc($modname, 'user', 'get', array('objectid' => $objectid));
            // Get extra information for the News module
            if ($modname == 'News') {
                $info = pnModAPIFunc('News', 'user', 'getArticleInfo', $item);
                $links = pnModAPIFunc('News', 'user', 'getArticleLinks', $info);
                $item = array_merge($info, $links);
            }
            $pnRender->assign($item);
        }
    }

    // Get the information on the current user
    if (pnUserLoggedIn()) {
        $pnRender->assign('uname', pnUserGetVar('uname'));
        $pnRender->assign('email', pnUserGetVar('email'));
    } else {
        $pnRender->assign('uname', pnConfigGetVar('anonymous'));
    }

    // Return the output that has been generated by this function
    return $pnRender->fetch('recommendus_user_view.htm');
}

/**
 * send e-mail
 *
 * This function sends the recomendation e-mail
 *
 * @author Mark West
 * @return bool true
 */
function Recommend_Us_user_send($args)
{
    $dom = ZLanguage::getModuleDomain('Recommend_Us');

    // Get parameters from whatever input we need
    $yourname        = FormUtil::getPassedValue('yourname', null, 'REQUEST');
    $youremail       = FormUtil::getPassedValue('youremail', null, 'REQUEST');
    $yourfriendname  = FormUtil::getPassedValue('yourfriendname', null, 'REQUEST');
    $yourfriendemail = FormUtil::getPassedValue('yourfriendemail', null, 'REQUEST');
    $modname         = FormUtil::getPassedValue('modname', null, 'REQUEST');
    $objectid        = (int)FormUtil::getPassedValue('objectid', null, 'REQUEST');

    // Security check
    if (!SecurityUtil::checkPermission('Recommend_Us::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Recommend_Us', 'user', 'view'));
    }

    // Parameter checks
    // 1) the name isn't too long
    if (strlen($yourfriendname)>25 || strlen($yourname)>25) {
        return DataUtil::formatForDisplayHTML(__('A name is too long', $dom));
    }

    // 2) the fmail is valid
    if (!pnVarValidate($yourfriendemail, 'email')) {
        return DataUtil::formatForDisplayHTML(__("Friend's email address is invalid", $dom));
    }

    // 3) the ymail is valid
    if (!pnVarValidate($youremail, 'email')) {
        return DataUtil::formatForDisplayHTML(__('Your email address is invalid', $dom));
    }

    // Construct and sent e-mail message
    $pnRender = pnRender::getInstance('Recommend_Us');

    // assign the user information for the message
    $pnRender->assign('yourname', $yourname);
    $pnRender->assign('yourfriendname', $yourfriendname);

    // load the module API and get the item
    if (isset($objectid) && is_numeric($objectid) &&
        isset($modname) && pnModAvailable($modname)) {
        $item = pnModAPIFunc($modname, 'user', 'get', array('objectid' => $objectid));
        $pnRender->assign('modname', $modname);
        $pnRender->assign('objectid', $objectid);
        $pnRender->assign($item);
        $subject = __f('Interesting item: %s', $item['title'], $dom);
        $returnargs = array('modname' => $modname, 'objectid' => $objectid);
    } else {
    	$subject = __f('Interesting site: %s', pnConfigGetVar('sitename'), $dom);
        $returnargs = array();
    }

    // construct and send the e-mail
    $message = $pnRender->fetch('recommendus_user_send.htm');
    $result = pnMail($yourfriendemail, $subject, $message);

    // Check the mail was sent ok
    if ($result != false) {
        // Success
        $message = __f('The reference to our site has been sent to %s, thanks for recommending us!', $yourfriendname, $dom);
        LogUtil::registerStatus ($message);
    }

    // This function generated no output, and so now it is complete we redirect
    // the user to an appropriate page for them to carry on their work
    return pnRedirect(pnModURL('Recommend_Us', 'user', 'view', $returnargs));
}

/**
 * display hook function to send urls to several social bookmark sites
 *
 * This function displays a list of links to send the url to a social bookmarl site like digg.com or del.icio.us
 *
 * @author Frank Schummertz
 * @param $args['objectid'] ID of the item to recommend
 * @param $args['extrainfo'] not used
 * @return string rendered template
 */
function Recommend_Us_user_display($args)
{
    PageUtil::addVar('stylesheet', 'modules/Recommend_Us/pnstyle/recommendus.css');

    $pnr = pnRender::getInstance('Recommend_Us');
    $pnr->assign('currenturl', DataUtil::formatForDisplayHTML(pnGetCurrentURL()));
    $pnr->assign('title', DataUtil::formatForDisplayHTML(PageUtil::getVar('title')));
    $pnr->assign('objectid', DataUtil::formatForDisplayHTML($objectid));

    return $pnr->fetch('recommendus_user_display.htm');
}
