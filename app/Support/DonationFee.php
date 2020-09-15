<?php

namespace App\Support;


use Exception;

class DonationFee
{
    const FIXED_FEE = 50;

    private $donation;
    private $commissionPercentage;

    public function __construct(int $donation, int $commissionPercentage)
    {
        if ($donation < 100 || $commissionPercentage < 0 || $commissionPercentage > 30)
        {
            throw new Exception('Commission Percentage should be an int');
        }

        $this->donation = $donation;
        $this->commissionPercentage = $commissionPercentage;
    }

    public function getCommissionAmount()
    {
        return $this->donation * $this->commissionPercentage / 100;
    }

    public function getFixedAndCommissionFeeAmount()
    {
        return $this->getCommissionAmount() + self::FIXED_FEE;
    }

    public function getAmountCollected()
    {
        return $this->donation - $this->getFixedAndCommissionFeeAmount();
    }


}