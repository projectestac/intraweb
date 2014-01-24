<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadmin.php 374 2009-12-06 07:13:50Z mateo $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Value_Addons
 * @subpackage Reviews
 */

/**
 * the main administration function
 *
 * @return string HTML output
 */
function Reviews_admin_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $render = & pnRender::getInstance('Reviews', false);

    // Return the output that has been generated by this function
    return $render->fetch('reviews_admin_main.htm');
}

/**
 * create a new news article
 * this function is purely a wrapper for the output from news_user_new
 * @author Mark West
 * @return string HTML string
 */
function Reviews_admin_new()
{
    // Return the output that has been generated by this function
    return pnModFunc('Reviews', 'user', 'new');
}

/**
 * modify an item
 *
 * @param 'id' the id of the item to be modified
 * @return string HTML output
 */
function Reviews_admin_modify($args)
{
    // Get parameters from whatever input we need
    $id       = (int)FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'REQUEST');
    $objectid = (int)FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    // At this stage we check to see if we have been passed $objectid
    if ($objectid) {
        $id = $objectid;
    }

    // Validate the essential parameters
    if (empty($id)) {
        return LogUtil::registerArgsError();
    }

    $dom = ZLanguage::getModuleDomain('Reviews');

    // Get the review
    $item = pnModAPIFunc('Reviews', 'user', 'get', array('id' => $id));

    if ($item === false) {
        return LogUtil::registerError(__('No such review found.', $dom), 404);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', "$item[title]::$id", ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    // Get the module configuration vars
    $modvars = pnModGetVar('Reviews');

    // Create output object
    $render = & pnRender::getInstance('Reviews', false);

    if ($modvars['enablecategorization']) {
        // load the category registry util
        if (!Loader::loadClass('CategoryRegistryUtil')) {
            pn_exit(__f('Error! Unable to load class [%s%]', 'CategoryRegistryUtil', $dom));
        }
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories ('Reviews', 'reviews');

        $render->assign('catregistry', $catregistry);
    }

    // Assign item to template
    $render->assign($item);

    // Pass the module configuration to the template
    $render->assign($modvars);

    // try to guarantee that only one person at a time can be editing this componentVersion
    pnModAPIFunc('PageLock', 'user', 'pageLock',
                 array('lockName' => "review{$item['id']}",
                       'returnUrl' => pnModUrl('Review', 'admin', 'view')));

    // Return the output that has been generated by this function
    return $render->fetch('reviews_admin_modify.htm');
}

/**
 * update review
 *
 * @param 'id' the id of the item to be updated
 * @param 'name' the name of the item to be updated
 * @param 'number' the number of the item to be updated
 */
function Reviews_admin_update($args)
{
    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('Reviews', 'admin', 'view'));
    }

    $review = FormUtil::getPassedValue('review', isset($args['review']) ? $args['review'] : null, 'POST');
    if (!empty($review['objectid'])) {
        $review['id'] = $review['objectid'];
    }

    // Validate the essential parameters
    if (empty($review['id'])) {
        return LogUtil::registerArgsError();
    }

    $dom = ZLanguage::getModuleDomain('Reviews');

    // Notable by its absence there is no security check here
    // Update the review
    if (pnModAPIFunc('Reviews', 'admin', 'update', $review)) {
        // Success
        LogUtil::registerStatus(__('Done! Review updated.', $dom));
    }

    pnModAPIFunc('PageLock', 'user', 'releaseLock',
                 array('lockName' => "review{$review['id']}"));

    return pnRedirect(pnModURL('Reviews', 'admin', 'view'));
}

/**
 * delete item
 * 
 * @param 'id' the id of the item to be deleted
 * @param 'confirmation' confirmation that this item can be deleted
 * @return mixed string HTML output if no confirmation otherwise true
 */
function Reviews_admin_delete($args)
{
    $id           = FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'REQUEST');
    $objectid     = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
    $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
    if (!empty($objectid)) {
        $id = $objectid;
    }

    // Validate the essential parameters
    if (empty($id)) {
        return LogUtil::registerArgsError();
    }

    $dom = ZLanguage::getModuleDomain('Reviews');

    // Get the existing review
    $item = pnModAPIFunc('Reviews', 'user', 'get', array('id' => $id));

    if ($item === false) {
        return LogUtil::registerError(__('No such review found.', $dom), 404);
    }

    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', "$item[title]::$id", ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    // Check for confirmation.
    if (empty($confirmation)) {
        // No confirmation yet
        // Create output object
        $render = & pnRender::getInstance('Reviews', false);

        // Add a hidden variable for the item id
        $render->assign('id', $id);

        // Return the output that has been generated by this function
        return $render->fetch('reviews_admin_delete.htm');
    }

    // If we get here it means that the user has confirmed the action

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError (pnModURL('Reviews', 'admin', 'view'));
    }

    // Delete the review
    if (pnModAPIFunc('Reviews', 'admin', 'delete', array('id' => $id))) {
        // Success
        LogUtil::registerStatus(__('Done! Review deleted.', $dom));
    }

    return pnRedirect(pnModURL('Reviews', 'admin', 'view'));
}

/**
 * view items
 *
 * @param int $startnum the start item id for the pager
 * @return string html string
 */
function Reviews_admin_view($args)
{
    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    $startnum = (int)FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : null, 'GET');
    $property = FormUtil::getPassedValue('reviews_property', isset($args['reviews_property']) ? $args['reviews_property'] : null, 'POST');
    $category = FormUtil::getPassedValue("reviews_{$property}_category", isset($args["reviews_{$property}_category"]) ? $args["reviews_{$property}_category"] : null, 'POST');
    $clear    = FormUtil::getPassedValue('clear', false, 'POST');
    $purge    = FormUtil::getPassedValue('purge', false, 'GET');

    $dom = ZLanguage::getModuleDomain('Reviews');

    if ($purge) {
        if (pnModAPIFunc('Reviews', 'admin', 'purgepermalinks')) {
            LogUtil::registerStatus(__('Purging of the pemalinks was successful', $dom));
        } else {
            LogUtil::registerError(__('Purging of the pemalinks has failed', $dom));
        }
        return pnRedirect(strpos(pnServerGetVar('HTTP_REFERER'), 'purge') ? pnModURL('Reviews', 'admin', 'view') : pnServerGetVar('HTTP_REFERER'));
    }
    if ($clear) {
        $property = null;
        $category = null;
    }

    // get module vars for later use
    $modvars = pnModGetVar('Reviews');

    if ($modvars['enablecategorization']) {
        // load the category registry util
        if (!Loader::loadClass('CategoryRegistryUtil')) {
            pn_exit(__f('Error! Unable to load class [%s%]', 'CategoryRegistryUtil', $dom));
        }
        $catregistry  = CategoryRegistryUtil::getRegisteredModuleCategories('Reviews', 'reviews');
        $properties = array_keys($catregistry);

        // Validate and build the category filter - mateo
        if (!empty($property) && in_array($property, $properties) && !empty($category)) {
            $catFilter = array($property => $category);
        }

        // Assign a default property - mateo
        if (empty($property) || !in_array($property, $properties)) {
            $property = $properties[0];
        }

        // plan ahead for ML features
        $propArray = array();
        foreach ($properties as $prop) {
            $propArray[$prop] = $prop;
        }
    }

    // Get all matching reviews
    $items = pnModAPIFunc('Reviews', 'user', 'getall',
                          array('startnum'    => $startnum,
                                'numitems'    => $modvars['itemsperpage'],
                                'category'    => isset($catFilter) ? $catFilter : null,
                                'catregistry' => isset($catregistry) ? $catregistry : null));

    if (!$items) {
        $items = array();
    }

    $reviewsitems = array();
    foreach ($items as $item)
    {
        $options = array();
        $options[] = array('url'   => pnModURL('Reviews', 'user', 'display', array('id' => $item['id'])),
                           'image' => 'demo.gif',
                           'title' => __('View', $dom));

        if (SecurityUtil::checkPermission('Reviews::', "$item[title]::$item[id]", ACCESS_EDIT)) {
            $options[] = array('url' => pnModURL('Reviews', 'admin', 'modify', array('id' => $item['id'])),
                               'image' => 'xedit.gif',
                               'title' => __('Edit', $dom));

            if (SecurityUtil::checkPermission('Reviews::', "$item[title]::$item[id]", ACCESS_DELETE)) {
                $options[] = array('url' => pnModURL('Reviews', 'admin', 'delete', array('id' => $item['id'])),
                                   'image' => '14_layer_deletelayer.gif',
                                   'title' => __('Delete', $dom));
            }
        }

        // Add the calculated menu options to the item array
        $item['options'] = $options;
        $reviewsitems[] = $item;
    }

    // Create output object
    $render = & pnRender::getInstance('Reviews', false);

    // Assign the items to the template
    $render->assign('reviews', $reviewsitems);

    // assign the module vars
    $render->assign($modvars);

    // Assign the default language
    $render->assign('lang', ZLanguage::getLanguageCode());

    // Assign the categories information if enabled
    if ($modvars['enablecategorization']) {
        $render->assign('catregistry', $catregistry);
        $render->assign('numproperties', count($propArray));
        $render->assign('properties', $propArray);
        $render->assign('property', $property);
        $render->assign("category", $category);
    }

    // Assign the values for the smarty plugin to produce a pager
    $render->assign('pager', array('numitems'     => pnModAPIFunc('Reviews', 'user', 'countitems', array('category' => isset($catFilter) ? $catFilter : null)),
                                   'itemsperpage' => $modvars['itemsperpage']));

    // Return the output that has been generated by this function
    return $render->fetch('reviews_admin_view.htm');
}

/**
 * This is a standard function to modify the configuration parameters of the
 * module
 *
 * @return string HTML output
 */
function Reviews_admin_modifyconfig()
{
    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
    $render = & pnRender::getInstance('Reviews', false);

    // Assign all module vars
    $render->assign(pnModGetVar('Reviews'));

    // Return the output that has been generated by this function
    return $render->fetch('reviews_admin_modifyconfig.htm');
}

/**
 * This is a standard function to update the configuration parameters of the
 * module given the information passed back by the modification form
 */
function Reviews_admin_updateconfig()
{
    // Security check
    if (!SecurityUtil::checkPermission('Reviews::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    // Confirm authorisation code
    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError(pnModURL('Reviews', 'admin', 'view'));
    }

    $dom = ZLanguage::getModuleDomain('Reviews');

    // Update module variables
    $itemsperpage = (int)FormUtil::getPassedValue('itemsperpage', 25, 'POST');
    if ($itemsperpage < 1) {
        $itemsperpage = 25;
    }
    pnModSetVar('Reviews', 'itemsperpage', $itemsperpage);

    $enablecategorization = (bool)FormUtil::getPassedValue('enablecategorization', false, 'POST');
    pnModSetVar('Reviews', 'enablecategorization', $enablecategorization);

    // Let any other modules know that the modules configuration has been updated
    pnModCallHooks('module', 'updateconfig', 'Reviews', array('module' => 'Reviews'));

    // the module configuration has been updated successfuly
    LogUtil::registerStatus(__('Done! Module configuration updated.', $dom));

    return pnRedirect(pnModURL('Reviews', 'admin', 'view'));
}