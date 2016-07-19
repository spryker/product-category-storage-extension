<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Handler\Request;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\FactFinder\Business\Api\ApiConstants;

class SearchRequest extends AbstractRequest implements RequestInterface
{
    
    const TRANSACTION_TYPE = ApiConstants::TRANSACTION_TYPE_SEARCH;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function request(QuoteTransfer $quoteTransfer)
    {
        $searchRequestTransfer = $quoteTransfer->getFfSearchRequest();

        // @todo @Artem : check do we need send request? 
        // $request = mapper->map($searchRequestTransfer);
        $searchAdapter = $this->ffConnector->createSearchAdapter();
        // @todo check
        $campaigns = $searchAdapter->getCampaigns();
        if ($campaigns->hasRedirect()) {
            //throw new RedirectException($campaigns->getRedirectUrl());
            $redirectUrl = $campaigns->getRedirectUrl();
        }

        $this->logInfo($quoteTransfer, $searchAdapter);
        
        // convert to FFSearchResponseTransfer
        $responseTransfer = $this->converterFactory
            ->createSearchResponseConverter($searchAdapter)
            ->convert();

        return $responseTransfer;
    }

}
