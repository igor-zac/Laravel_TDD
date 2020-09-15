<?php

namespace App\Support;


use Exception;

class DonationFee
{

    private $donation;
    private $commissionPercentage;

    public function __construct(int $donation, int $commissionPercentage)
    {
        $this->donation = $donation;

        if ($commissionPercentage < 0 || $commissionPercentage > 30)
        {
            throw new Exception('Commission Percentage should be an int');
        }

        $this->commissionPercentage = $commissionPercentage;
    }

    public function getCommissionAmount()
    {
        return $this->donation * $this->commissionPercentage / 100;
    }

    public function getAmountCollected()
    {
        return $this->donation - $this->getCommissionAmount();
    }
}