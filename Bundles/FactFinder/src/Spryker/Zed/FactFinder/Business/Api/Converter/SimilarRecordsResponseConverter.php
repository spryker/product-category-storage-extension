<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter;

use FACTFinder\Adapter\SimilarRecords as FFSimilarRecords;
use Generated\Shared\Transfer\FfSimilarRecordsResponseTransfer;

class SimilarRecordsResponseConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Adapter\SimilarRecords
     */
    protected $similarRecordsAdapter;

    /**
     * @param \FACTFinder\Adapter\SimilarRecords $similarRecordsAdapter
     */
    public function __construct(FFSimilarRecords $similarRecordsAdapter)
    {
        $this->similarRecordsAdapter = $similarRecordsAdapter;
    }

    /**
     * @return \Generated\Shared\Transfer\FfSimilarRecordsResponseTransfer
     */
    public function convert()
    {
        $responseTransfer = new FfSimilarRecordsResponseTransfer();
//        $responseTransfer->set();

        return $responseTransfer;
    }

}
