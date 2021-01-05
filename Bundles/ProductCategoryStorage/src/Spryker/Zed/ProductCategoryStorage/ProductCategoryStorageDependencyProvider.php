<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToCategoryBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer\ProductCategoryStorageToProductQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig getConfig()
 */
class ProductCategoryStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    public const FACADE_CATEGORY = 'FACADE_CATEGORY';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_STORE = 'FACADE_STORE';

    public const PROPEL_QUERY_CATEGORY_NODE = 'PROPEL_QUERY_CATEGORY_NODE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductCategoryStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new ProductCategoryStorageToCategoryBridge($container->getLocator()->category()->facade());
        });

        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductCategoryStorageToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return new ProductCategoryStorageToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductCategoryStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        $container = $this->addCategoryNodeQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductCategoryStorageToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryNodeQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_NODE, $container->factory(function () {
            return SpyCategoryNodeQuery::create();
        }));

        return $container;
    }
}
