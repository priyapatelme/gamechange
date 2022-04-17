<?php
    
namespace GameChange\ProductList\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface; 
use Magento\Catalog\Block\Product\ProductList\Toolbar; 
use Magento\Catalog\Model\Category; 
use Magento\Catalog\Model\Config; 
use Magento\Catalog\Model\Layer; 
use Magento\Catalog\Model\Layer\Resolver; 
use Magento\Catalog\Model\Product; 
use Magento\Catalog\Model\ResourceModel\Product\Collection; 
use Magento\Catalog\Pricing\Price\FinalPrice; 
use Magento\Framework\App\ActionInterface; 
use Magento\Framework\App\Config\Element; 
use Magento\Framework\Data\Helper\PostHelper; 
use Magento\Framework\DataObject\IdentityInterface; 
use Magento\Framework\Exception\NoSuchEntityException; 
use Magento\Framework\Pricing\Render; 
use Magento\Framework\Url\Helper\Data;
use GameChange\ProductList\Helper\HelperData;

class GameChangeLink extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_defaultToolbarBlock = Toolbar::class;
    protected $_productCollection;
    protected $_catalogLayer;
    protected $_postDataHelper;
    protected $urlHelper;
    protected $categoryRepository;
    private $logger;
    protected $helperData;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = [],
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        HelperData $helperData
    ) {
        $this->_customerSession = $customerSession;
        $this->categoryFactory = $categoryFactory;
        $this->helperData = $helperData;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }
        return $this->_productCollection;
    }

    public function getLoadedProductCollection()
    {
        return $this->_getProductCollection();
    }

    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        if ($this->getShowRootCategory()) {
            $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        }

        if ($this->_coreRegistry->registry('product')) {
            $categories = $this->_coreRegistry->registry('product')
                ->getCategoryCollection()->setPage(1, 1)
                ->load();
            if ($categories->count()) {
                $this->setCategoryId(current($categories->getIterator())->getId());
            }
        }

        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }
        $collection = $layer->getProductCollection();

        $productLimit = $this->helperData->getProductLimit();
        $collection->addAttributeToFilter('handle_display', 1)->setPageSize($productLimit);

        $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );
       return $collection;
    }

    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }   

    public function getProductLimit()
    {
        return $this->helperData->getProductLimit();
    }        
}