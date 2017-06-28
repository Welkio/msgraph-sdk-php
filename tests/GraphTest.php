<?php
use PHPUnit\Framework\TestCase;
use Microsoft\Graph\Core\GraphConstants;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Http\GraphRequest;

class GraphTest extends TestCase
{
    public function testGraphConstructor()
    {
        $graph = new Graph();
        $this->assertNotNull($graph);
    }

    public function testInitializeEmptyGraph()
    {
        $this->expectException(Microsoft\Graph\Exception\GraphException::class);
        $graph = new Graph();
        $request = $graph->createRequest("GET", "/me");
    }

    public function testInitializeGraphWithToken()
    {
        $graph = new Graph();
        $graph->setAccessToken('abc');
        $request = $graph->createRequest("GET", "/me");

        $this->assertInstanceOf(GraphRequest::class, $request);
    }

    public function testCreateCollectionRequest()
    {
        $graph = new Graph();
        $graph->setAccessToken('abc');
        $request = $graph->createCollectionRequest("GET", "/me");

        $this->assertInstanceOf(GraphRequest::class, $request);
    }

    public function testRequestWithCustomEndpoint()
    {
        $graph = new Graph();
        $graph->setAccessToken('abc');
        $graph->setBaseUrl('url2');

        $request = $graph->createRequest("GET", "/me");
        $requestUrl = $this->readAttribute($request, 'baseUrl');
        $this->assertEquals('url2', $requestUrl);
    }

    public function testBetaRequest()
    {
        $graph = new Graph();
        $graph->setAccessToken('abc');
        $request = $graph->createRequest("GET", "/me")->setApiVersion('/beta');

        $this->assertEquals('/beta', $this->readAttribute($request, 'apiVersion'));
    }
}