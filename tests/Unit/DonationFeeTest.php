<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Support\DonationFee;
use Exception;

class DonationFeeTest extends TestCase
{

    public function testCommissionAmountIs10CentsFormDonationOf100CentsAndCommissionOf10Percent()
    {
        // Etant donné une donation de 100 et commission de 10%
        $donationFees = new DonationFee(100, 10);

        // Lorsque qu'on appel la méthode getCommissionAmount()
        $actual = $donationFees->getCommissionAmount();

        // Alors la Valeur de la commission doit être de 10
        $expected = 10;
        $this->assertEquals($expected, $actual);
    }

    public function testCommissionAmountIs20CentsFormDonationOf200CentsAndCommissionOf10Percent()
    {
        // Etant donné une donation de 100 et commission de 10%
        $donationFees = new DonationFee(200, 10);

        // Lorsque qu'on appel la méthode getCommissionAmount()
        $actual = $donationFees->getCommissionAmount();

        // Alors la Valeur de la commission doit être de 20
        $expected = 20;
        $this->assertEquals($expected, $actual);
    }

    public function testAmountCollectedIs180CentsForDonationOf200CentsAndCommissionOf10Percent()
    {
        //Given
        $donationFees = new DonationFee(200, 10);

        //When
        $perceivedAmount = $donationFees->getAmountCollected();

        //Then
        $expected = 180;
        $this->assertEquals($expected, $perceivedAmount);
    }

    public function testCommissionPercentageLowerThan0PercentShouldThrowException()
    {
        // Given
        $commissionPercentage = -3;

        //Then
        $this->expectException(Exception::class);
        $donationFees = new DonationFee(200, $commissionPercentage);
    }

    public function testCommissionPercentageEqualTo0ShouldntThrowAnException()
    {
        // Given
        $commissionPercentage = 0;

        // When
        try {
            $donationFees = new DonationFee(200, $commissionPercentage);
            $this->assertTrue(true);

        //Then
        } catch (Exception $e) {
            $this->fail("Exception thrown for commission percentage of 0");
        }
    }

    public function testCommissionPercentageBetween0And30ShouldntThrowAnException()
    {
        // Given
        $commissionPercentage = 15;

        // When
        try {
            $donationFees = new DonationFee(200, $commissionPercentage);
            $this->assertTrue(true);

        //Then
        } catch (Exception $e) {
            $this->fail("Exception thrown for commission percentage between 0 and 30");
        }
    }

    public function testCommissionPercentageEqualTo30ShouldntThrowAnException()
    {
        // Given
        $commissionPercentage = 30;

        // When
        try {
            $donationFees = new DonationFee(200, $commissionPercentage);
            $this->assertTrue(true);

        //Then
        } catch (Exception $e) {
            $this->fail("Exception thrown for commission percentage of 30");
        }
    }

    public function testCommissionPercentageGreaterThan30ShouldThrowAnException()
    {
        // Given
        $commissionPercentage = 35;

        //Then
        $this->expectException(Exception::class);
        $donationFees = new DonationFee(200, $commissionPercentage);
    }

    public function testDonationAmountLowerThan100ShouldThrowAnException()
    {
        //Given
        $donation = 80;

        //Then
        $this->expectException(Exception::class);
        $donationFees = new DonationFee($donation, 10);
    }
    
    public function testDonationAmountEqualTo100ShouldntThrowAnException()
    {
        // Given
        $donation = 100;

        // When
        try {
            $donationFees = new DonationFee($donation, 10);
            $this->assertTrue(true);

        //Then
        } catch (Exception $e) {
            $this->fail("Exception thrown for donation of 100");
        }
    }

    public function testDonationAmountGreaterThan100ShouldntThrowAnException()
    {
        // Given
        $donation = 120;

        // When
        try {
            $donationFees = new DonationFee($donation, 10);
            $this->assertTrue(true);

        //Then
        } catch (Exception $e) {
            $this->fail("Exception thrown for donation greater than 100");
        }
    }
}
