<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

interface OmsRepositoryInterface
{
    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array;

    /**
     * @param string[] $stateNames
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getSalesOrderAggregationBySkuAndStatesNames(array $stateNames, string $sku, StoreTransfer $storeTransfer): array;

    /**
     * @param string[] $concreteSkus
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityByConcreteProductSkusForStore(array $concreteSkus, int $idStore): Decimal;
}
