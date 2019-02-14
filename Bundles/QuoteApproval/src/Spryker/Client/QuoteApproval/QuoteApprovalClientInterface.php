<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalClientInterface
{
    /**
     * Specification:
     * - Calculates approval status for quote.
     * - Returns status `Approved` if at least one approval request has status `Approved`.
     * - Returns status `Waiting` if at least one approval request in status `Waiting` and there is no `Approved` requests.
     * - Returns status `Declined` if all all approval requests are declined.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string;

    /**
     * Specification:
     * - Makes zed request.
     * - Clears current cart sharing.
     * - Shares quote to approver with read only access.
     * - Locks quote.
     * - Creates QuoteApproval with `Waiting` status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function createQuoteApproval(
        QuoteApprovalCreateRequestTransfer $quoteApprovalCreateRequestTransfer
    ): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Unlocks quote.
     * - Removes cart sharing with approver.
     * - Removes quote approval request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(
        QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
    ): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Makes zed request.
     * - Returns collection of company users that can approve quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getQuoteApprovers(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer;

    /**
     * Specification:
     * - Returns false if customer does't have RequestQuoteApprovalPermissionPlugin permission assigned.
     * - Returns false if executing of PlaceOrderPermissionPlugin permission returns true.
     * - Returns false if quote approval status is `approved`.
     * - Returns true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteRequireApproval(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if quote status is `waiting`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteWaitingForApproval(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if quote status is `approved`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApproved(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns highest limit calculated from all ApproveQuotePermissionPlugin permissions assigned to company user.
     * - Returns null if no permissions found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculateApproveQuotePermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int;

    /**
     * Specification:
     * - Returns highest limit calculated from all PlaceOrderPermissionPlugin permissions assigned to company user.
     * - Returns null if no permissions found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return int|null
     */
    public function calculatePlaceOrderPermissionLimit(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer): ?int;

    /**
     * Specification:
     * - Sends Zed request to approve quote approval request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function approveQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Sends Zed request to decline quote approval request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function declineQuoteApproval(QuoteApprovalRequestTransfer $quoteApprovalRequestTransfer): QuoteApprovalResponseTransfer;

    /**
     * Specification:
     * - Returns quote approval which waiting for approve from specified company user.
     * - Returns null if approval not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalTransfer|null
     */
    public function findWaitingQuoteApprovalByIdCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): ?QuoteApprovalTransfer;

    /**
     * Specification:
     * - Returns true if customer has `ApproveQuotePermissionPlugin` and it's execution returns true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteCanBeApprovedByCurrentCustomer(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Returns true if at least 1 approval request assigned to specified company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idCompanyUser
     *
     * @return bool
     */
    public function hasQuoteApprovalsForCompanyUser(QuoteTransfer $quoteTransfer, int $idCompanyUser): bool;
}
