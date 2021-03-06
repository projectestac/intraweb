<?php

/**
 * Zikula Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadmin.php 27274 2009-10-30 13:49:20Z mateo $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.tpll
 * @package Zikula_System_Modules
 * @subpackage AdminMessages
 * @license http://www.gnu.org/copyleft/gpl.tpll
 */
class AdminMessages_Controller_Admin extends Zikula_AbstractController {

    public function postInitialize() {
        $this->view->setCaching(false);
    }

    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.  As such it can
     * be used for a number of things, but most commonly it either just
     * shows the module menu and returns or calls whatever the module
     * designer feels should be the default function (often this is the
     * view() function)
     * @author Mark West
     * @return string HTML output string
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // Return the output that has been generated by this function
        return $this->view->fetch('admin_messages_admin_main.tpl');
    }

    /**
     * add a new admin message
     * This is a standard function that is called whenever an administrator
     * wishes to create a new module item
     * @author Mark West
     * @return string HTML output string
     */
    public function newItem() {
        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }

        $multilingual = System::getVar('multilingual');
        // Assign the default language
        return $this->view->assign('language', ZLanguage::getLanguageCode())
                        ->assign('multilingual', $multilingual)
                        ->fetch('admin_messages_admin_new.tpl');
    }

    /**
     * This is a standard function that is called with the results of the
     * form supplied by AdminMessages_admin_new() to create a new item
     * @author Mark West
     * @see AdminMessages_admin_new()
     * @param string $args['title'] the title of the admin message
     * @param string $args['content'] the text of the admin message
     * @param string $args['language'] the language of the admin message
     * @param int $args['active'] active status of the admin message
     * @param int $args['expire'] the expiry date of the message
     * @param int $args['view'] who can view the message
     * @return bool true if creation successful, false otherwiise
     */
    public function create($args) {
        $message = FormUtil::getPassedValue('message', isset($args['message']) ? $args['message'] : null, 'POST');

        // Confirm authorisation code.
        $this->checkCsrfToken();

        // Notable by its absence there is no security check here.
        // Create the admin message
        $mid = ModUtil::apiFunc('AdminMessages', 'admin', 'create', array('title' => $message['title'],
                    'content' => $message['content'],
                    'language' => isset($message['language']) ? $message['language'] : '',
                    'active' => $message['active'],
                    'expire' => $message['expire'],
                    'view' => $message['view']));

        // The return value of the function is checked
        if ($mid != false) {
            // Success
            LogUtil::registerStatus($this->__('Done! Created administrator message.'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('AdminMessages', 'admin', 'view'));
    }

    /**
     * modify an Admin Message
     * This is a standard function that is called whenever an administrator
     * wishes to modify a current module item
     * @author Mark West
     * @param int $args['mid'] the id of the admin message to modify
     * @param int $args['objectid'] generic object id maps to mid if present
     * @return string HTML output string
     */
    public function modify($args) {
        $mid = FormUtil::getPassedValue('mid', isset($args['mid']) ? $args['mid'] : null, 'GET');
        $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'GET');

        if (!empty($objectid))
            $mid = $objectid;

        // Get the admin message
        $item = ModUtil::apiFunc('AdminMessages', 'user', 'get', array('mid' => $mid));

        if ($item == false) {
            return LogUtil::registerError($this->__('Sorry! No such administrator message found.'), 404);
        }

        // Security check.
        if (!SecurityUtil::checkPermission('AdminMessages::item', "$item[title]::$mid", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        $multilingual = System::getVar('multilingual');

        // Assign the item
        return $this->view->assign('item', $item)
                        ->assign('multilingual', $multilingual)
                        ->fetch('admin_messages_admin_modify.tpl');
    }

    /**
     * This is a standard function that is called with the results of the
     * form supplied by AdminMessages_admin_modify() to update a current item
     * @author Mark West
     * @see AdminMessages_admin_modify()
     * @param int $args['mid'] the id of the admin message to update
     * @param int $args['objectid'] generic object id maps to mid if present
     * @param string $args['title'] the title of the admin message
     * @param string $args['content'] the text of the admin message
     * @param string $args['language'] the language of the admin message
     * @param int $args['active'] active status of the admin message
     * @param int $args['expire'] the expiry date of the message
     * @param int $args['view'] who can view the message
     * @return bool true if successful, false otherwise
     */
    public function update($args) {
        $message = FormUtil::getPassedValue('message', isset($args['message']) ? $args['message'] : null, 'POST');
        if (!empty($message['objectid'])) {
            $message['mid'] = $message['objectid'];
        }

        // Confirm authorisation code.
        $this->checkCsrfToken();

        // Notable by its absence there is no security check here.
        // Update the admin message
        if (ModUtil::apiFunc('AdminMessages', 'admin', 'update', array('mid' => $message['mid'],
                    'title' => $message['title'],
                    'content' => $message['content'],
                    'language' => isset($message['language']) ? $message['language'] : '',
                    'active' => $message['active'],
                    'expire' => $message['expire'],
                    'oldtime' => $message['oldtime'],
                    'changestartday' => $message['changestartday'],
                    'view' => $message['view']))) {
            // Success
            LogUtil::registerStatus($this->__('Done! Saved administrator message.'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('AdminMessages', 'admin', 'view'));
    }

    /**
     * delete item
     * This is a standard function that is called whenever an administrator
     * wishes to delete a current module item.  Note that this function is
     * the equivalent of both of the modify() and update() functions above as
     * it both creates a form and processes its output.  This is fine for
     * simpler functions, but for more complex operations such as creation and
     * modification it is generally easier to separate them into separate
     * functions.  There is no requirement in the Zikula MDG to do one or the
     * other, so either or both can be used as seen appropriate by the module
     * developer
     * @author Mark West
     * @param int $args['mid'] the id of the admin message to delete
     * @param int $args['objectid'] generic object id maps to mid if present
     * @param bool $args['confirmation'] confirmation of the deletion
     * @return mixed HTML output string if no confirmation, true if succesful, false otherwise
     */
    public function delete($args) {
        $mid = FormUtil::getPassedValue('mid', isset($args['mid']) ? $args['mid'] : null, 'REQUEST');
        $objectid = FormUtil::getPassedValue('objectid', isset($args['objectid']) ? $args['objectid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
        if (!empty($objectid)) {
            $mid = $objectid;
        }

        // Get the existing admin message
        $item = ModUtil::apiFunc('AdminMessages', 'user', 'get', array('mid' => $mid));

        if ($item == false) {
            return LogUtil::registerError($this->__('Sorry! No such administrator message found.'), 404);
        }

        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', "$item[title]::$mid", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Create output object
            $view = Zikula_View::getInstance('AdminMessages', false);

            // Add the message id
            $view->assign('mid', $mid);

            // Return the output that has been generated by this function
            return $view->fetch('admin_messages_admin_delete.tpl');
        }

        // If we get here it means that the user has confirmed the action
        // Confirm authorisation code.
        $this->checkCsrfToken();

        // Delete the admin message
        // The return value of the function is checked
        if (ModUtil::apiFunc('AdminMessages', 'admin', 'delete', array('mid' => $mid))) {
            // Success
            LogUtil::registerStatus($this->__('Done! Deleted administrator message.'));
        }

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('AdminMessages', 'admin', 'view'));
    }

    /**
     * view items
     * @author Mark West
     * @param int $startnum the start item id for the pager
     * @return string HTML output string
     */
    public function view() {
        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        $startnum = FormUtil::getPassedValue('startnum', isset($args['startnum']) ? $args['startnum'] : null, 'GET');

        // The user API function is called.  This takes the number of items
        // required and the first number in the list of all items, which we
        // obtained from the input and gets us the information on the appropriate
        // items.
        $items = ModUtil::apiFunc('AdminMessages', 'user', 'getall', array('startnum' => $startnum,
                    'numitems' => ModUtil::getVar('AdminMessages', 'itemsperpage')));

        if (!$items)
            $items = array();

        $rows = array();
        foreach ($items as $item) {

            if (SecurityUtil::checkPermission('AdminMessages::', "$item[title]::$item[mid]", ACCESS_READ)) {

                $fullitem = ModUtil::apiFunc('AdminMessages', 'user', 'get', array('mid' => $item['mid']));

                if ($fullitem['language'] == '')
                    $fullitem['language'] = $this->__('All');

                $row[] = $fullitem['language'];

                if (!isset($fullitem['view']))
                    $fullitem['view'] = $this->__('All visitors');
                switch ($fullitem['view']) {
                    case '1':
                        $fullitem['view'] = $this->__('All visitors');
                        break;
                    case '2':
                        $fullitem['view'] = $this->__('Registered users only');
                        break;
                    case '3':
                        $fullitem['view'] = $this->__('Anonymous guests only');
                        break;
                    case '4':
                        $fullitem['view'] = $this->__('Administrators only');
                        break;
                }
                $row[] = $fullitem['view'];

                $active = ($fullitem['active'] == 1) ? $this->__('Yes') : $active = $this->__('No');

                if ($fullitem['expire'] == 0) {
                    $expire = $this->__('Never');
                } else if ($fullitem['expire'] / 86400 == 1) {
                    $expire = $fullitem['expire'] / 86400 . ' ' . $this->__('day');
                } else {
                    $expire = $fullitem['expire'] / 86400 . ' ' . $this->__('days');
                }

                $expiredate = ($fullitem['expire'] == 0) ? $this->__('No expiration date') : $expiredate = date('M d, Y', $fullitem['date'] + $fullitem['expire']);

                // Options for the item.  Note that each item has the appropriate
                // levels of authentication checked to ensure that it is suitable
                // for display
                $options = array();
                if (SecurityUtil::checkPermission('AdminMessages::', "$item[title]::$item[mid]", ACCESS_EDIT)) {
                    $options[] = array('url' => ModUtil::url('AdminMessages', 'admin', 'modify', array('mid' => $item['mid'])),
                        'image' => 'xedit.png',
                        'title' => $this->__('Edit'));
                    if (SecurityUtil::checkPermission('AdminMessages::', "$item[title]::$item[mid]", ACCESS_DELETE)) {
                        $options[] = array('url' => ModUtil::url('AdminMessages', 'admin', 'delete', array('mid' => $item['mid'])),
                            'image' => '14_layer_deletelayer.png',
                            'title' => $this->__('Delete'));
                    }
                }
                $rows[] = array('mid' => $item['mid'],
                    'title' => $item['title'],
                    'language' => $fullitem['language'],
                    'view' => $fullitem['view'],
                    'active' => $active,
                    'expire' => $expire,
                    'expiredate' => $expiredate,
                    'options' => $options);
            }
        }

        return $this->view->assign('items', $rows)
                        ->assign('pager', array('numitems' => ModUtil::apiFunc('AdminMessages', 'user', 'countitems'),
                            'itemsperpage' => ModUtil::getVar('AdminMessages', 'itemsperpage')))
                        ->fetch('admin_messages_admin_view.tpl');
    }

    /**
     * This is a standard function to modify the configuration parameters of the
     * module
     * @author Mark West
     * @return string HTML output string
     */
    public function modifyconfig() {
        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Number of items to display per page
        return $this->view->assign(ModUtil::getVar('AdminMessages'))
                        ->fetch('admin_messages_admin_modifyconfig.tpl');
    }

    /**
     * This is a standard function to update the configuration parameters of the
     * module given the information passed back by the modification form
     * @author Mark West
     * @see AdminMessages_admin_modifyconfig()
     * @param int $itemsperpage the number messages per page in the admin panel
     * @return bool true if successful, false otherwise
     */
    public function updateconfig() {
        // Security check
        if (!SecurityUtil::checkPermission('AdminMessages::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code.
        $this->checkCsrfToken();

        // Update module variables.
        $itemsperpage = (int) FormUtil::getPassedValue('itemsperpage', 25, 'POST');
        if ($itemsperpage < 1)
            $itemsperpage = 25;
        ModUtil::setVar('AdminMessages', 'itemsperpage', $itemsperpage);

        $allowsearchinactive = (bool) FormUtil::getPassedValue('allowsearchinactive', false, 'POST');
        ModUtil::setVar('AdminMessages', 'allowsearchinactive', $allowsearchinactive);

        // the module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Done! Saved module configuration.'));

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        return System::redirect(ModUtil::url('AdminMessages', 'admin', 'view'));
    }

}