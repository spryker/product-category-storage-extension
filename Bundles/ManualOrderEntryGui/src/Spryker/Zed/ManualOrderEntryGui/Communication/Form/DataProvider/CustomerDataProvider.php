<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerDataProvider
{

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CustomerTransfer::class
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getData(CustomerTransfer $customerTransfer)
    {
        return $customerTransfer;
    }

}
