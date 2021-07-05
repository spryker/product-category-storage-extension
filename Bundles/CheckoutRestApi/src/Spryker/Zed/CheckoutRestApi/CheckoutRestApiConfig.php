<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CheckoutRestApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function deleteCartAfterOrderCreation()
    {
        return true;
    }
}
