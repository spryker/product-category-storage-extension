<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business;

use Generated\Shared\Payolution\OrderInterface;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 */
class PayolutionFacade extends AbstractFacade
{

    /**
     * @param OrderInterface $orderTransfer
     */
    public function saveOrderPayment(OrderInterface $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createOrderSaver()
            ->saveOrderPayment($orderTransfer);
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionResponseTransfer
     */
    public function preCheckPayment(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionResponseTransfer = $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->preCheckPayment($checkoutRequestTransfer);

        return $payolutionResponseTransfer;
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function preAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->preAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function reAuthorizePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->reAuthorizePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function revertPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->revertPayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function capturePayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->capturePayment($idPayment);
    }

    /**
     * @param int $idPayment
     *
     * @return PayolutionResponseTransfer
     */
    public function refundPayment($idPayment)
    {
        return $this
            ->getDependencyContainer()
            ->createPaymentCommunicator()
            ->refundPayment($idPayment);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isPreAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isReAuthorizationApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isReversalApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isCaptureApproved($orderTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this
            ->getDependencyContainer()
            ->createTransactionStatusLog()
            ->isRefundApproved($orderTransfer);
    }

}
