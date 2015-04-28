<?php

namespace SprykerFeature\Shared\Payone\Transfer;


class Refund extends Transaction implements RefundDataInterface
{

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var PaymentInterface
     */
    protected $payment;


    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return PaymentInterface
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param PaymentInterface $payment
     */
    public function setPayment(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

}