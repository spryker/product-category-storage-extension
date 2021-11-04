<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlistsRestApi\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\ProductConfigurationWishlist\Communication\Plugin\Wishlist\ProductConfigurationWishlistPreUpdateItemPlugin;
use Spryker\Zed\Wishlist\WishlistDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationWishlistsRestApi
 * @group Business
 * @group UpdateWishlistItemTest
 * Add your own group annotations below this line
 */
class UpdateWishlistItemTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'FAKE_SKU_1';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateWishlistItemUpdatesWishlistItem(): void
    {
        // Arrange
        $this->tester->setDependency(WishlistDependencyProvider::PLUGINS_WISHLIST_PRE_UPDATE_ITEM, [
            new ProductConfigurationWishlistPreUpdateItemPlugin(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $wishlistItemTransfer = $this->tester->createWishlistItemWithProductConfigurationInstance(
            $customerTransfer,
            (new ProductConfigurationInstanceTransfer())->setDisplayData('{}'),
        );

        $productConfigurationInstanceHash = $this->tester
            ->getProductConfigurationInstanceHash($wishlistItemTransfer->getProductConfigurationInstance());

        $newProductConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setDisplayData('{"test": "test"}');

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', $wishlistItemTransfer->getSku(), $productConfigurationInstanceHash))
            ->setSku($wishlistItemTransfer->getSku())
            ->setProductConfigurationInstance($newProductConfigurationInstance);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemRequestTransfer, new ArrayObject([$wishlistItemTransfer]));

        // Assert
        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            $this->tester->getLocator()->utilEncoding()->service()->encodeJson($newProductConfigurationInstance->toArray()),
            $this->tester->findWishlistItemById($wishlistItemTransfer->getIdWishlistItem())->getProductConfigurationInstanceData(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemAvoidUpdateCase(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $wishlistItemTransfer = $this->tester->createWishlistItemWithProductConfigurationInstance(
            $customerTransfer,
            (new ProductConfigurationInstanceTransfer())->setDisplayData('{}'),
        );

        $productConfigurationInstanceHash = $this->tester
            ->getProductConfigurationInstanceHash($wishlistItemTransfer->getProductConfigurationInstance());

        $newProductConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setDisplayData('{"test": "test"}');

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_1, $productConfigurationInstanceHash))
            ->setSku(static::FAKE_SKU_1)
            ->setProductConfigurationInstance($newProductConfigurationInstance);

        // Act
        $wishlistItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemRequestTransfer, new ArrayObject([$wishlistItemTransfer]));

        // Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateWishlistItemUpdatesWishlistItemWithoutProductConfiguration(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $wishlistItemTransfer = $this->tester->createWishlistItemWithProductConfigurationInstance(
            $customerTransfer,
            (new ProductConfigurationInstanceTransfer())->setDisplayData('{}'),
        );

        $productConfigurationInstanceHash = $this->tester
            ->getProductConfigurationInstanceHash($wishlistItemTransfer->getProductConfigurationInstance());

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', $wishlistItemTransfer->getSku(), $productConfigurationInstanceHash))
            ->setSku($wishlistItemTransfer->getSku())
            ->setProductConfigurationInstance(null);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->updateWishlistItem($wishlistItemRequestTransfer, new ArrayObject([$wishlistItemTransfer]));
    }
}
