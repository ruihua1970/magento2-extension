<?php

namespace Ess\M2ePro\Controller\Adminhtml\MigrationFromMagento1;

use \Magento\Backend\App\Action;

use \Ess\M2ePro\Helper\Factory as HelperFactory;

abstract class Base extends Action
{
    /** @var HelperFactory $helperFactory */
    protected $helperFactory = NULL;

    /** @var \Magento\Framework\Controller\Result\RawFactory $resultRawFactory  */
    protected $resultRawFactory = NULL;

    /** @var \Magento\Framework\App\ResourceConnection|null  */
    protected $resourceConnection = NULL;

    /** @var \Magento\Framework\Controller\Result\Raw $rawResult  */
    protected $rawResult = NULL;

    //########################################

    public function __construct(\Ess\M2ePro\Controller\Adminhtml\Context $context)
    {
        $this->helperFactory = $context->getHelperFactory();
        $this->resultRawFactory = $context->getResultRawFactory();
        $this->resourceConnection = $context->getResourceConnection();

        parent::__construct($context);
    }

    //########################################

    protected function _isAllowed()
    {
        return $this->_auth->isLoggedIn();
    }

    //########################################

    protected function getRawResult()
    {
        if (is_null($this->rawResult)) {
            $this->rawResult = $this->resultRawFactory->create();
        }

        return $this->rawResult;
    }

    //########################################

    protected function __()
    {
        return $this->getHelper('Module\Translation')->translate(func_get_args());
    }

    /**
     * @param $helperName
     * @param array $arguments
     * @return \Magento\Framework\App\Helper\AbstractHelper
     * @throws \Ess\M2ePro\Model\Exception\Logic
     */
    protected function getHelper($helperName, array $arguments = [])
    {
        return $this->helperFactory->getObject($helperName, $arguments);
    }

    //########################################
}