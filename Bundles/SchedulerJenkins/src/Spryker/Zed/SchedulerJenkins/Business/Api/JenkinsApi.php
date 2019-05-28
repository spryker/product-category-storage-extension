<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerResponseTransfer;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig as SharedSchedulerJenkinsConfig;
use Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound;
use Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

class JenkinsApi implements JenkinsApiInterface
{
    protected const JENKINS_URL_API_CSRF_TOKEN = 'crumbIssuer/api/xml?xpath=concat(//crumbRequestField,":",//crumb)';

    protected const HEADERS_KEY = 'headers';
    protected const BODY_KEY = 'body';
    protected const AUTH_KEY = 'auth';

    protected const SUCCESS_STATUS_CODE = 200;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected $schedulerJenkinsConfig;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface $client
     * @param \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig $schedulerJenkinsConfig
     */
    public function __construct(
        SchedulerJenkinsToGuzzleInterface $client,
        SchedulerJenkinsConfig $schedulerJenkinsConfig
    ) {
        $this->client = $client;
        $this->schedulerJenkinsConfig = $schedulerJenkinsConfig;
    }

    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function executeGetRequest(string $schedulerId, string $urlPath): ResponseInterface
    {
        $requestUrl = $this->getJenkinsBaseUrlBySchedulerId($schedulerId, $urlPath);
        $response = $this->client->get($requestUrl);

        return $response;
    }

    /**
     * @param string $schedulerId
     * @param string $urlPath
     * @param string $xmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    public function executePostRequest(string $schedulerId, string $urlPath, string $xmlTemplate = ''): SchedulerResponseTransfer
    {
        try {
            $requestUrl = $this->getJenkinsBaseUrlBySchedulerId($schedulerId, $urlPath);
            $requestOptions = $this->getRequestOptions($schedulerId, $xmlTemplate);
            $response = $this->client->post($requestUrl, $requestOptions);
        } catch (BadResponseException $badResponseException) {
            $exceptionMessage = $badResponseException->getResponse()->getBody()->getContents();

            return (new SchedulerResponseTransfer())
                ->setMessage($exceptionMessage)
                ->setStatus(false);
        }

        return (new SchedulerResponseTransfer())
            ->setStatus($response->getStatusCode() === static::SUCCESS_STATUS_CODE);
    }

    /**
     * @param string $schedulerId
     * @param string $xmlTemplate
     *
     * @return array
     */
    protected function getRequestOptions(string $schedulerId, string $xmlTemplate = ''): array
    {
        $requestOptions = [
            static::HEADERS_KEY => $this->getHeaders($xmlTemplate),
            static::BODY_KEY => $xmlTemplate,
            static::AUTH_KEY => $this->getJenkinsAuthCredentials($schedulerId),
        ];

        return $requestOptions;
    }

    /**
     * @param string $xmlTemplate
     *
     * @return array
     */
    protected function getHeaders(string $xmlTemplate = ''): array
    {
        $httpHeader = [];

        if ($xmlTemplate) {
            $httpHeader = [
                'Content-Type' => 'text/xml; charset=UTF8',
            ];
        }

        if ($this->schedulerJenkinsConfig->isJenkinsCsrfProtectionEnabled()) {
            $httpHeader[] = $this->client->get(static::JENKINS_URL_API_CSRF_TOKEN);
        }

        return $httpHeader;
    }

    /**
     * @param string $schedulerId
     *
     * @return array
     */
    protected function getJenkinsConfigurationBySchedulerId(string $schedulerId): array
    {
        $schedulerJenkinsConfiguration = $this->schedulerJenkinsConfig->getJenkinsConfiguration();

        return $schedulerJenkinsConfiguration[$schedulerId];
    }

    /**
     * @param string $schedulerId
     *
     * @return string[]
     */
    protected function getJenkinsAuthCredentials(string $schedulerId): array
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($schedulerId);

        if (!isset($schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS])) {
            return [];
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_CREDENTIALS];
    }

    /**
     * @param string $schedulerId
     * @param string $urlPath
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\JenkinsBaseUrlNotFound
     *
     * @return string
     */
    protected function getJenkinsBaseUrlBySchedulerId(string $schedulerId, string $urlPath): string
    {
        $schedulerJenkinsConfiguration = $this->getJenkinsConfigurationBySchedulerId($schedulerId);

        if (!isset($schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL])) {
            throw new JenkinsBaseUrlNotFound();
        }

        return $schedulerJenkinsConfiguration[SharedSchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL] . $urlPath;
    }
}
