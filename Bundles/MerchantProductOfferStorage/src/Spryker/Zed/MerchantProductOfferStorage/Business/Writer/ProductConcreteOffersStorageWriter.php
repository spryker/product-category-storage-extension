<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface
     */
    protected $productConcreteOffersStorageDeleter;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager,
        ProductConcreteOffersStorageDeleterInterface $productConcreteOffersStorageDeleter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
        $this->productConcreteOffersStorageDeleter = $productConcreteOffersStorageDeleter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductConcreteProductOffersStorageCollectionByProductSkuEvents(array $eventTransfers): void
    {
        $productSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_CONCRETE_SKU);

        if (!$productSkus) {
            return;
        }

        $this->writeProductConcreteProductOffersStorageCollectionByProductSkus($productSkus);
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    protected function writeProductConcreteProductOffersStorageCollectionByProductSkus(array $productSkus): void
    {
        $productSkus = array_unique($productSkus);
        $productOfferCriteriaFilterTransfer = $this->createProductOfferCriteriaFilterTransfer($productSkus);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        $productOffersGroupedBySku = $this->groupProductOfferByConcreteSku($productOfferCollectionTransfer);

        $storedProductSkus = [];
        foreach ($productOffersGroupedBySku as $concreteSku => $productOfferReferenceList) {
            $this->merchantProductOfferStorageEntityManager->saveProductConcreteProductOffersStorage($concreteSku, $productOfferReferenceList);
            $storedProductSkus[] = $concreteSku;
        }

        $productSkusToDelete = array_diff($productSkus, $storedProductSkus);
        $this->productConcreteOffersStorageDeleter->deleteProductConcreteProductOffersStorageCollectionByProductSkus($productSkusToDelete);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function groupProductOfferByConcreteSku(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOffersGroupedBySku = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!isset($productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()])) {
                $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()] = [];
            }
            $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()][] = mb_strtolower($productOfferTransfer->getProductOfferReference());
        }

        return $productOffersGroupedBySku;
    }

    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(array $productSkus): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setConcreteSkus($productSkus)
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }
}