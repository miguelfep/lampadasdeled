<?php

/* * ****************************************************
 * Package   : Advancedreports
 * Author    : http://www.arrowhitech.com
 * Copyright : (c) 2013 - ArrowHiTech.Com
 * ***************************************************** */
?>
<?php

class MGS_Advancedreports_Model_Mysql4_Report_Salesbynewandreturning_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected $_periodFormat;
    protected $_selectedColumns = array();
    protected $_from = null;
    protected $_to = null;
    protected $_orderStatus = null;
    protected $_period = null;
    protected $_storesIds = 0;
    protected $_applyFilters = true;
    protected $_isTotals = false;
    protected $_isSubTotals = false;
    protected $_aggregatedColumns = array();

    /**
     * Initialize custom resource model
     */
    public function __construct() {
        parent::_construct();
        $this->setModel('adminhtml/report_item');
        $this->_resource = Mage::getResourceModel('advancedreports/salesbynewandreturning')->init('advancedreports/salesbynewandreturning');
        $this->setConnection($this->getResource()->getReadConnection());
    }

    /**
     * Get selected columns
     *
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _getSelectedColumns() {
        //if ('month' == $this->_period) {
        //    $this->_periodFormat = 'DATE_FORMAT(created_at, \'%Y-%m\')';
        //} elseif ('year' == $this->_period) {
        //    $this->_periodFormat = 'EXTRACT(YEAR FROM created_at)';
        //} else {
        //    $this->_periodFormat = 'DATE_FORMAT(created_at, \'%Y-%m-%d\')';
        //}
        if ($this->isTotals()) {
            $this->_selectedColumns = $this->getAggregatedColumns();
        } else {
            $this->_selectedColumns = array(
                'period' => 'period',
                'new_customers' => 'IFNULL(new_customers, 0)',
                'returning_customers' => 'IFNULL(returning_customers, 0)',
                'percent_of_new' => 'percent_of_new',
                'percent_of_returning' => 'percent_of_returning'
            );
        }
        return $this->_selectedColumns;
    }

    /**
     * Add selected data
     *
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _initSelect() {
        $this->getSelect()->from($this->getResource()->getMainTable(), $this->_getSelectedColumns());
        if (!$this->isTotals()) {
            $this->getSelect()->order('n ASC');
            $this->getSelect()->group('period');
        }
        return $this;
    }

    /**
     * Set array of columns that should be aggregated
     *
     * @param array $columns
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function setAggregatedColumns(array $columns) {
        $this->_aggregatedColumns = $columns;
        return $this;
    }

    /**
     * Retrieve array of columns that should be aggregated
     *
     * @return array
     */
    public function getAggregatedColumns() {
        return $this->_aggregatedColumns;
    }

    /**
     * Set date range
     *
     * @param mixed $from
     * @param mixed $to
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function setDateRange($from = null, $to = null) {
        $this->_from = $from;
        $this->_to = $to;
        return $this;
    }

    /**
     * Set period
     *
     * @param string $period
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function setPeriod($period) {
        $this->_period = $period;
        return $this;
    }

    /**
     * Apply date range filter
     *
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _applyDateRangeFilter() {
        if (!is_null($this->_from)) {
            $this->_from = date('Y-m-d G:i:s', strtotime($this->_from));
            $this->getSelect()->where('created_at >= ?', $this->_from);
        }
        if (!is_null($this->_to)) {
            $this->_to = date('Y-m-d G:i:s', strtotime($this->_to));
        }
        $this->getSelect()->where('created_at <= ?', $this->_to);
        return $this;
    }

    /**
     * Set store ids
     *
     * @param mixed $storeIds (null, int|string, array, array may contain null)
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function addStoreFilter($storeIds) {
        $this->_storesIds = $storeIds;
        return $this;
    }

    /**
     * Apply stores filter to select object
     *
     * @param Zend_Db_Select $select
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _applyStoresFilterToSelect(Zend_Db_Select $select) {
        $storeIds = explode(',', Mage::app()->getRequest()->getParam('store_ids'));
        $arrStoreIds = array_diff($storeIds, array(''));
        if (count($arrStoreIds)) {
            $select->where('store_id IN(?)', $arrStoreIds);
        }
        return $this;
    }

    /**
     * Apply stores filter
     *
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _applyStoresFilter() {
        return $this->_applyStoresFilterToSelect($this->getSelect());
    }

    /**
     * Set status filter
     *
     * @param string|array $state
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function addOrderStatusFilter($orderStatus) {
        $this->_orderStatus = $orderStatus;
        return $this;
    }

    /**
     * Apply order status filter
     *
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    protected function _applyOrderStatusFilter() {
        if (is_null($this->_orderStatus)) {
            return $this;
        }
        $orderStatus = $this->_orderStatus;
        if (!is_array($orderStatus)) {
            $orderStatus = array($orderStatus);
        }
        $this->getSelect()->where('status IN(?)', $orderStatus);
        return $this;
    }

    /**
     * Set apply filters flag
     *
     * @param boolean $flag
     * @return MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function setApplyFilters($flag) {
        $this->_applyFilters = $flag;
        return $this;
    }

    /**
     * Getter/Setter for isTotals
     *
     * @param null|boolean $flag
     * @return boolean|MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function isTotals($flag = null) {
        if (is_null($flag)) {
            return $this->_isTotals;
        }
        $this->_isTotals = $flag;
        return $this;
    }

    /**
     * Getter/Setter for isSubTotals
     *
     * @param null|boolean $flag
     * @return boolean|MGS_Advancedreports_Model_Mysql4_Report_Salesbydayofweek_Collection
     */
    public function isSubTotals($flag = null) {
        if (is_null($flag)) {
            return $this->_isSubTotals;
        }
        $this->_isSubTotals = $flag;
        return $this;
    }

    /**
     * Load data
     * Redeclare parent load method just for adding method _beforeLoad
     *
     * @return  Varien_Data_Collection_Db
     */
    public function load($printQuery = false, $logQuery = false) {
        if ($this->isLoaded()) {
            return $this;
        }
        $this->_initSelect();
        if ($this->_applyFilters) {
            //$this->_applyDateRangeFilter();
            //$this->_applyOrderStatusFilter();
            //$this->_applyStoresFilter();            
        }
        return parent::load($printQuery, $logQuery);
    }

}