<?php

namespace Unistra\UtilBundle\Tests\Command\Template;

use Unistra\UtilBundle\Command\Template\TemplateFetcher;

class TemplateFetcherTest extends \PHPUnit_Framework_TestCase
{
    protected $templateFile = '/tmp/fetch.html.twig';

    public function setUp()
    {
        $this->templateFile = sprintf('/tmp/fetch%s.html.twig', getmypid());
        $this->urlToFetch = 'http://www.unistra.fr/index.php?id=etudes';
        $this->xslFile = __DIR__.'/../../../Resources/xsl/templates/base.xsl';
        $this->checks = [ '//h1[@id="page-title"]' ];
    }

    public function tearDown()
    {
        if (is_file($this->templateFile)) {
            unlink($this->templateFile);
        }
    }

    public function testFetch()
    {
        $fetcher = new TemplateFetcher();
        $this->assertInstanceOf('Unistra\UtilBundle\Command\Template\TemplateFetcher', $fetcher);
        $fetcher->setChecks($this->checks);
        $fetcher->fetch($this->urlToFetch, $this->templateFile, $this->xslFile);
        $this->assertFileExists($this->templateFile);
    }

    /**
     * @expectedException   \InvalidArgumentException
     */
    public function testBadPage()
    {
        $fetcher = new TemplateFetcher();
        $fetcher->fetch('http://www.unistra.fr/index.php?id=999999999999999', $this->templateFile, $this->xslFile);
    }

    public function testBadChecks()
    {
        $fetcher = new TemplateFetcher();
        $fetcher->setChecks([ '//valise[@id="inexistante"]' ]);
        try {
            $fetcher->fetch($this->urlToFetch, $this->templateFile, $this->xslFile);
        } catch (\Exception $e) {
            @unlink($this->templateFile);

            return;
        }
        $this->fail('une exception aurait du être levée avec un test xpath impassable');
    }

    public function testSecondCallDoesNotRefetechDistantPage()
    {
        $fetcher = new TemplateFetcher();
        $fetcher->fetch($this->urlToFetch, $this->templateFile, $this->xslFile);
        $fetcher->fetch($this->urlToFetch, $this->templateFile, $this->xslFile);
    }

}
