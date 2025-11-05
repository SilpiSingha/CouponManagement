<?php

namespace Silpi\CouponManagement\Model;

use Silpi\CouponManagement\Api\DiscountRulesRepositoryInterface;
use Silpi\CouponManagement\Api\Data\DiscountRulesInterface;
use Silpi\CouponManagement\Model\DiscountRulesFactory;
use Silpi\CouponManagement\Model\ResourceModel\DiscountRules\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Silpi\CouponManagement\Model\ResourceModel\DiscountRules;

class DiscountRulesRepository implements DiscountRulesRepositoryInterface
 {
    /**
    * DiscountRulesFactory
    *
    * @var \Silpi\CouponManagement\Model\DiscountRulesFactory
    */
    protected $discountRulesFactory;

    /**
    * DataPageFactory
    *
    * @var \Silpi\CouponManagement\Api\Data\DiscountRulesInterfaceFactory
    */
    protected $dataPageFactory;

    /**
    * DataObjectHelper
    *
    * @var \Magento\Framework\Api\DataObjectHelper
    */
    protected $dataObjectHelper;

    /**
    * DataObjectProcessor
    *
    * @var \Magento\Framework\Reflection\DataObjectProcessor
    */
    protected $dataObjectProcessor;

    /**
    * CollectionFactory
    *
    * @var \Silpi\CouponManagement\Model\ResourceModel\DiscountRules\CollectionFactory
    */
    protected $collectionFactory;

    /**
    * DiscountRulesResourceModel
    *
    * @var SearchResultsInterfaceFactory
    */
    protected $searchResultsFactory;

    /**
    * DiscountRulesResourceModel
    *
    * @var \Silpi\CouponManagement\Model\ResourceModel\DiscountRules
    */
    protected $discountRulesResource;

    /**
    * Constructor DiscountRulesRepository
    *
    * @param DiscountRulesFactory                $discountRulesFactory       discountRulesFactory
    * @param CollectionFactory             $collectionFactory    collectionFactory
    * @param DataObjectHelper              $dataObjectHelper     dataObjectHelper
    * @param DataObjectProcessor           $dataObjectProcessor  dataObjectProcessor
    * @param \Silpi\CouponManagement\Api\Data\DiscountRulesInterfaceFactory           $dataPageFactory      dataPageFactory
    * @param SearchResultsInterfaceFactory $searchResultsFactory searchResultsFactory
    * @param discountRules                       $discountRulesResource          discountRulesResource
    */

    public function __construct(
        DiscountRulesFactory $discountRulesFactory,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        \Silpi\CouponManagement\Api\Data\DiscountRulesInterfaceFactory $dataPageFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        DiscountRules $discountRulesResource
    ) {
        $this->discountRulesFactory        = $discountRulesFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPageFactory = $dataPageFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->discountRulesResource = $discountRulesResource;
    }

    /**
    * Save DiscountRulesData
    *
    * @param object $object \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
    *
    * @return object
    * @throws Magento\Framework\Exception\CouldNotSaveException
    */

    public function save( DiscountRulesInterface $object )
    {
        if ( empty( $object->getStoreId() ) ) {
            $storeId = $this->storeManager->getStore()->getId();
            $object->setStoreId( $storeId );
        }
        try {
            $this->resource->save( $object );
        } catch ( \Exception $e ) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the DiscountRules: %1',
                    $e->getMessage()
                )
            );
        }
        return $object;
    }

    /**
    * Get DiscountRules By Id
    *
    * @param int $id discountRulesid
    *
    * @return object
    * @throws \Magento\Framework\Exception\NoSuchEntityException
    */

    public function getById( $id )
    {
        $discountRulesModel = $this->discountRulesFactory->create();
        $this->discountRulesResource->load( $discountRulesModel, $id );
        if ( !$discountRulesModel->getId() ) {
            throw new NoSuchEntityException(
                __( 'Object with id "%1" does not exist.', $id )
            );
        }
        return $discountRulesModel;
    }

    /**
    * Delete DiscountRules
    *
    * @param object $object \Silpi\CouponManagement\Api\Data\DiscountRulesInterface
    *
    * @return boolean
    * @throws \Magento\Framework\Exception\CouldNotDeleteException
    */

    public function delete( DiscountRulesInterface $object )
    {
        try {
            $object->delete();
        } catch ( \Exception $e ) {
            throw new CouldNotDeleteException( __( $e->getMessage() ) );
        }
        return true;
    }

    /**
    * Delete DiscountRules By Id
    *
    * @param int $id discountRulesid
    *
    * @return void
    */

    public function deleteById( $id )
    {
        return $this->delete( $this->getById( $id ) );
    }
}
