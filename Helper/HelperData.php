<?php

namespace GameChange\ProductList\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class HelperData extends \Magento\Framework\App\Helper\AbstractHelper
{     
    /**
     * Admin configuration paths
     *
     */
    const IS_ENABLED = 'productlist/general/enable';

    const PRODUCT_LIMIT = 'productlist/general/productlimit';	
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
 
    /**
     * Data constructor
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);     
    }

    /**
     * @return $isEnabled
     */
    public function isEnabled()
    {
        $isEnabled = $this->scopeConfig->getValue(self::IS_ENABLED, 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $isEnabled;
    }

    /**
     * @return $productLimit
     */
    public function getProductLimit()
    {
        $productLimit = $this->scopeConfig->getValue(self::PRODUCT_LIMIT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $productLimit;
    }
}