<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Block_Adminhtml_Report_Salesbynewandreturning extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_blockGroup = 'advancedreports';
        $this->_controller = 'adminhtml_report_salesbynewandreturning';
        $this->_headerText = Mage::helper('advancedreports')->__('New and Returning Customers');
        $this->setTemplate('mgs/advancedreports/report/salesbynewandreturning/grid/container.phtml');
        parent::__construct();
        $this->_removeButton('add');
        $this->addButton('filter_form_submit', array(
            'label' => Mage::helper('advancedreports')->__('Show Report'),
            'onclick' => 'filterFormSubmit()'
        ));
    }

    public function getFilterUrl() {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/salesbynewandreturning', array('_current' => true));
    }

    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_grid', $this->_controller . '.grid')->setSaveParametersInSession(true));
        return parent::_prepareLayout();
    }

}