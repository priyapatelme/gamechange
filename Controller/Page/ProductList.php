<?php 

namespace GameChange\ProductList\Controller\Page;

use GameChange\ProductList\Helper\HelperData;

class ProductList extends \Magento\Framework\App\Action\Action 
{ 
    protected $helperData;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        HelperData $helperData
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->customerSession=$customerSession;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    public function execute() {

        if (!$this->customerSession->create()->isLoggedIn()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        } else {

            $isEnabled = $this->helperData->isEnabled();

            if ($isEnabled) {

                $this->_view->loadLayout(); 
                $this->_view->renderLayout(); 
            } else {
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('customer/account/index');
                return $resultRedirect;
            }
        }   
    }
}