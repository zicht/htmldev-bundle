<?php
/**
 * @copyright Zicht Online <http://www.zicht.nl>
 */

namespace Zicht\Bundle\HtmldevBundle\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Zicht\Bundle\HtmldevBundle\Service\SvgService;

class SvgServiceTest extends TestCase
{
    /** @var SvgService */
    private $service;

    /**
     * Initializes resources shared by all tests in this test case.
     */
    public function setUp(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $this->service = new SvgService(sys_get_temp_dir(), $cache, $logger);
    }

    /**
     * Helper function for quickly creating an XML document.
     *
     * @param string $s
     * @return \DOMNode
     */
    protected function getNodeFromSvgString($s)
    {
        $d = new \DOMDocument();
        $d->loadXML($s);

        return $d->getElementsByTagName('svg')->item(0);
    }

    /**
     * Helper function for quickly saving an XML document to string.
     *
     * @param \DOMElement $element
     * @return string
     */
    protected function elementToString(\DOMElement $element)
    {
        return trim($element->ownerDocument->saveHTML());
    }

    /**
     * @test
     */
    public function setSvgAttributeAddsProperty()
    {
        $svg = $this->getNodeFromSvgString('<svg width="32px" />');

        $this->service->setSvgAttribute($svg, 'height', '32px', true);

        $this->assertEquals('<svg width="32px" height="32px"></svg>', $this->elementToString($svg));
    }

    /**
     * @test
     */
    public function setSvgAttributeReplacesProperty()
    {
        $svg = $this->getNodeFromSvgString('<svg width="32px" />');

        $this->service->setSvgAttribute($svg, 'width', '64px', true);

        $this->assertEquals('<svg width="64px"></svg>', $this->elementToString($svg));
    }

    /**
     * @test
     */
    public function setSvgAttributeLeavesPropertyAlone()
    {
        $svg = $this->getNodeFromSvgString('<svg width="32px" />');

        $this->service->setSvgAttribute($svg, 'width', '64px');

        $this->assertEquals('<svg width="32px"></svg>', $this->elementToString($svg));
    }
}
