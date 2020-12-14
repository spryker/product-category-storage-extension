<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Storage;

use Generated\Shared\Transfer\AuthContextTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SecurityBlockerStorage implements SecurityBlockerStorageInterface
{
    /**
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESPONSE_INVALID_LOGIN
     */
    protected const RESPONSE_INVALID_LOGIN = '003';

    /**
     * @uses \Spryker\Glue\AuthRestApi\AuthRestApiConfig::RESOURCE_ACCESS_TOKENS
     */
    protected const RESOURCE_ACCESS_TOKENS = 'access-tokens';

    /**
     * @var \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    protected $securityBlockerClient;

    /**
     * @param \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient
     */
    public function __construct(SecurityBlockerRestApiToSecurityBlockerClientInterface $securityBlockerClient)
    {
        $this->securityBlockerClient = $securityBlockerClient;
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return void
     */
    public function incrementLoginAttempts(
        string $action,
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): void {
        if (
            !$this->isAuthenticationRequest($action, $restRequest)
            || !$this->isFailedAuthenticationResponse($restResponse)
        ) {
            return;
        }

        /** @var \Generated\Shared\Transfer\RestAccessTokensAttributesTransfer $restAccessTokensAttributesTransfer */
        $restAccessTokensAttributesTransfer = $restRequest->getResource()->getAttributes();
        $authContextTransfer = (new AuthContextTransfer())
            ->setAccount(SecurityBlockerRestApiConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE)
            ->setIp($restRequest->getHttpRequest()->getClientIp())
            ->setAccount($restAccessTokensAttributesTransfer->getUsername());

        $this->securityBlockerClient->incrementLoginAttempt($authContextTransfer);
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isAuthenticationRequest(string $action, RestRequestInterface $restRequest): bool
    {
        return in_array($action, [static::RESOURCE_ACCESS_TOKENS])
            && $restRequest->getHttpRequest()->getMethod() === 'POST';
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return bool
     */
    protected function isFailedAuthenticationResponse(RestResponseInterface $restResponse): bool
    {
        if ($restResponse->getStatus() !== Response::HTTP_UNAUTHORIZED) {
            return false;
        }

        foreach ($restResponse->getErrors() as $restErrorMessageTransfer) {
            if ($restErrorMessageTransfer->getCode() === static::RESPONSE_INVALID_LOGIN) {
                return true;
            }
        }

        return false;
    }
}
