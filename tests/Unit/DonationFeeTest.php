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

    public function testAmountCollectedIs130CentsForDonationOf200CentsAndCommissionOf10Percent()
    {
        //Given
        $donation = 200;
        $commissionPercentage = 10;
        $fixedFee = 50;
        $donationFees = new DonationFee($donation, $commissionPercentage);

        //When
        $perceivedAmount = $donationFees->getAmountCollected();

        //Then
        $expected = $donation - ($fixedFee + $donation * $commissionPercentage / 100); //130
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
        $donationFees = new DonationFee(200, $commissionPercentage);

        // Then
        $this->assertInstanceOf(DonationFee::class, $donationFees);
    }

    public function testCommissionPercentageBetween0And30ShouldntThrowAnException()
    {
        // Given
        $commissionPercentage = 15;

        // When
        $donationFees = new DonationFee(200, $commissionPercentage);

        // Then
        $this->assertInstanceOf(DonationFee::class, $donationFees);
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

    public function testFixedAndCommissionFeeAmountIs60CentsForDonationOf100CentsAndCommissionOf10Percent()
    {
        // Given
        $donationFees = new DonationFee(100, 10);

        // When
        $actual = $donationFees->getFixedAndCommissionFeeAmount();

        // Then
        $expected = 60;
        $this->assertEquals($expected, $actual);
    }

    public function testFixedAndCommissionFeeAmountIsNeverGreaterThan500Cents()
    {
        // Given
        $donation1 = 2000;
        $donation2 = 5000;
        $donation3 = 10000000;

        // When
        $donationFees1 = new DonationFee($donation1, 10);
        $donationFees2 = new DonationFee($donation2, 10);
        $donationFees3 = new DonationFee($donation3, 10);

        $actual1 = $donationFees1->getFixedAndCommissionFeeAmount();
        $actual2 = $donationFees2->getFixedAndCommissionFeeAmount();
        $actual3 = $donationFees3->getFixedAndCommissionFeeAmount();

        //Then
        $expected = 500;
        $this->assertLessThanOrEqual($expected, $actual1);
        $this->assertLessThanOrEqual($expected, $actual2);
        $this->assertLessThanOrEqual($expected, $actual3);
    }

    public function testGetSummaryReturnsArrayWithExpectedArrayKeys()
    {
        // Given
        $donationFees = new DonationFee(100, 10);

        // When
        $summary = $donationFees->getSummary();

        //Then
        $this->assertIsArray($summary);

        $this->assertContains("donation", array_keys($summary));
        $this->assertContains("fixedFee", array_keys($summary));
        $this->assertContains("commission", array_keys($summary));
        $this->assertContains("fixedAndCommission", array_keys($summary));
        $this->assertContains("amountCollected", array_keys($summary));
    }

    public function testGetSummaryReturnsExpectedSummaryAssociativeArray()
    {
        // Given
        $donationFees = new DonationFee(100, 10);

        // When
        $summary = $donationFees->getSummary();

        //Then
        $this->assertCount(5, $summary);

        $expectedDonation = 100;
        $expectedFixedFee = 50;
        $expectedCommission = 10;
        $expectedFixedAndCommission = 60;
        $expectedAmountCollected = 40;

        $this->assertEquals($expectedDonation, $summary['donation']);
        $this->assertEquals($expectedFixedFee, $summary['fixedFee']);
        $this->assertEquals($expectedCommission, $summary['commission']);
        $this->assertEquals($expectedFixedAndCommission, $summary['fixedAndCommission']);
        $this->assertEquals($expectedAmountCollected, $summary['amountCollected']);
    }
}
