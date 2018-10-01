<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface;

class ShoppingListProductOptionWriter implements ShoppingListProductOptionWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface
     */
    protected $shoppingListProductOptionEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface $shoppingListProductOptionEntityManager
     */
    public function __construct(
        ShoppingListProductOptionEntityManagerInterface $shoppingListProductOptionEntityManager
    ) {
        $this->shoppingListProductOptionEntityManager = $shoppingListProductOptionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            $this->executeSaveShoppingListItemProductOptionsTransaction($shoppingListItemTransfer);
        });

        return $shoppingListItemTransfer;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $this->shoppingListProductOptionEntityManager
            ->removeShoppingListItemProductOptions($idShoppingListItem);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function executeSaveShoppingListItemProductOptionsTransaction(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $this->removeShoppingListItemProductOptions($shoppingListItemTransfer->getIdShoppingListItem());

        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->shoppingListProductOptionEntityManager
                ->saveShoppingListItemProductOption(
                    $shoppingListItemTransfer->getIdShoppingListItem(),
                    $productOptionTransfer->getIdProductOptionValue()
                );
        }
    }
}
