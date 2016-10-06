<?php

namespace Zicht\Bundle\HtmldevBundle\Tests;

use PHPUnit_Framework_TestCase;
use Zicht\Bundle\HtmldevBundle\Service\HtmlService;

/**
 * Tests for the HtmlService class.
 */
class HtmlServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HtmlService
     */
    private $service;

    /**
     * Initializes resources shared by all tests in this test case.
     */
    protected function setUp()
    {
        $this->service = new HtmlService();
    }

    /**
     * @test
     */
    public function getClassesSupportsSingleString()
    {
        $classes = $this->service->getClasses('art-vandelay');

        $this->assertEquals('art-vandelay', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsMultipleStrings()
    {
        $classes = $this->service->getClasses('art-vandelay', 'kramerica');

        $this->assertEquals('art-vandelay  kramerica', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsArrayWithSingleEntry()
    {
        $classes = $this->service->getClasses(['art-vandelay']);

        $this->assertEquals('art-vandelay', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsStringWithArray()
    {
        $classes = $this->service->getClasses('art-vandelay', ['kramerica', 'kel-varnsen']);

        $this->assertEquals('art-vandelay  kramerica  kel-varnsen', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsArrayWithMultipleEntries()
    {
        $classes = $this->service->getClasses(['art-vandelay', 'kramerica']);

        $this->assertEquals('art-vandelay  kramerica', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsObjectWithTruePredicate()
    {
        $classes = $this->service->getClasses(['art-vandelay' => true]);

        $this->assertEquals('art-vandelay', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsObjectWithFalsePredicate()
    {
        $classes = $this->service->getClasses(['art-vandelay' => false]);

        $this->assertEmpty($classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsObjectWithMultipleTruePredicates()
    {
        $classes = $this->service->getClasses(['art-vandelay' => true, 'kramerica' => true]);

        $this->assertEquals('art-vandelay  kramerica', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsObjectWithMultipleFalsePredicates()
    {
        $classes = $this->service->getClasses(['art-vandelay' => false, 'kramerica' => false]);

        $this->assertEmpty($classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsObjectWithMultipleMixedPredicates()
    {
        $classes = $this->service->getClasses(['art-vandelay' => true, 'kramerica' => false, 'kel-varnsen' => true]);

        $this->assertEquals('art-vandelay  kel-varnsen', $classes);
    }

    /**
     * @test
     */
    public function getClassesSupportsStringAndObjectWithMultipleMixedPredicates()
    {
        $classes = $this->service->getClasses('jerry', ['art-vandelay' => true, 'kramerica' => false, 'kel-varnsen' => false]);

        $this->assertEquals('jerry  art-vandelay', $classes);
    }

    /**
     * @test
     */
    public function getClassesDoesNotBreakOnUnsupportedTypes()
    {
        $classes = $this->service->getClasses(new \stdClass(), false, 0, 1, true, null);

        $this->assertEmpty($classes);
    }
}