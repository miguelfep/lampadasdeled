<?php

class MGS_Mpanel_Model_Mysql4_Home_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mpanel/home');
    }
}