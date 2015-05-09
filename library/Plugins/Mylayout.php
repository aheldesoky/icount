<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plugins_Mylayout
 *
 * @author ahmed
 */
class Plugins_Mylayout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $table  = new Zend_Db_Table('group');
        $select = $table->select();
        $groups = $db->fetchAll($select);
        
        $view->navGroups = $groups;
    }
}
