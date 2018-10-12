<?php
use Pakard\RestClient\MockTransport;
use Pakard\RestClient\Request;
use Pakard\RestClient\RequestInterface;
use Pakard\RestClient\Response;
use Pakard\RestClient\ResponseInterface;
use Pakard\RestClient\RestClient;

/**
 *
 */
class RestClientTest extends PHPUnit\Framework\TestCase {
    /**
     * @covers \Pakard\RestClient\RestClient::getBaseUrl()
     * @covers \Pakard\RestClient\RestClient::setBaseUrl()
     */
    public function testBaseUrl() {
        // Testing default.
        $this->assertNull((new RestClient)->getBaseUrl());

        // Testing setter return value (should be the same RestClient instance).
        $this->assertSame(
            $client = new RestClient,
            $client->setBaseUrl('test')
        );

        // Testing getter/setter transitivity.
        $this->assertSame(
            'test',
            (new RestClient)->setBaseUrl('test')->getBaseUrl()
        );
    }

    /**
     * @return array
     */
    public function urlDataProvider() {
        return [
            'no base' => [NULL, 'test', NULL, '/test'],
            'with base' => ['http://example.com/', '/test', NULL, 'http://example.com/test'],
            'base only' => ['http://example.com/test', NULL, NULL, 'http://example.com/test'],
            'string params' => ['http://example.com', 'test', 'egy=1&ketto=2', 'http://example.com/test?egy=1&ketto=2'],
            'array params' => ['http://example.com', 'test', ['egy' => 1, 'ketto' => 2], 'http://example.com/test?egy=1&ketto=2'],
            'object params' => ['http://example.com', 'test', (object) ['egy' => 1, 'ketto' => 2], 'http://example.com/test?egy=1&ketto=2'],
            'query string dupe' => ['http://example.com', 'test?egy=1', ['ketto' => 2], 'http://example.com/test?egy=1&ketto=2'],
        ];
    }

    /**
     * @param string $baseUrl
     * @param string $url
     * @param mixed $params
     * @param string $expected
     * @dataProvider urlDataProvider()
     * @covers \Pakard\RestClient\RestClient::prepareUrl()
     */
    public function testPrepareUrl($baseUrl, $url, $params, $expected) {
        $this->assertSame(
            $expected,
            (new RestClient)
                ->setBaseUrl($baseUrl)
                ->prepareUrl($url, $params)
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::getTransport()
     * @covers \Pakard\RestClient\RestClient::setTransport()
     */
    public function testTransport() {
        // Testing default.
        $this->assertNull((new RestClient)->getTransport());

        // Testing setter return value (should be the same RestClient instance).
        $this->assertSame(
            $client = new RestClient,
            $client->setTransport(new MockTransport)
        );

        // Testing getter/setter transitivity.
        $this->assertSame(
            $transport = new MockTransport,
            (new RestClient)->setTransport($transport)->getTransport()
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::createRequest()
     */
    public function testCreateRequest() {
        $this->assertInstanceOf(
            RequestInterface::class,
            (new RestClient)->createRequest()
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::getRequest()
     * @covers \Pakard\RestClient\RestClient::getResponse()
     */
    public function testRequest() {
        // Testing default.
        $this->assertNull((new RestClient)->getRequest());

        // Testing setter return value (should be the same RestClient instance).
        $this->assertSame(
            $client = new RestClient,
            $client->setRequest(new Request)
        );

        // Testing getter/setter transitivity.
        $this->assertSame(
            $request = new Request,
            (new RestClient)->setRequest($request)->getRequest()
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::createResponse()
     */
    public function testCreateResponse() {
        $this->assertInstanceOf(
            ResponseInterface::class,
            (new RestClient)->createResponse()
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::getResponse()
     * @covers \Pakard\RestClient\RestClient::setResponse()
     */
    public function testResponse() {
        // Testing default.
        $this->assertNull((new RestClient)->getResponse());

        // Testing setter return value (should be the same RestClient instance).
        $this->assertSame(
            $client = new RestClient,
            $client->setResponse(new Response)
        );

        // Testing getter/setter transitivity.
        $this->assertSame(
            $response = new Response,
            (new RestClient)->setResponse($response)->getResponse()
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::before()
     * @covers \Pakard\RestClient\RestClient::callBeforeCallbacks()
     */
    public function testBefore() {
        $called1 = FALSE;
        $called2 = FALSE;

        // Testing with no callback set, also testing caller return value.
        $this->assertSame(
            $client = new RestClient,
            $client->callBeforeCallbacks()
        );

        // Testing setter return value (should be the same RestClient instance).
        // Preparing callback to be able to check if client is the same.
        $this->assertSame(
            $client,
            $client->before(
                function ($argument) use ($client, &$called1) {
                    $this->assertSame($client, $argument);
                    $called1 = TRUE;
                }
            )
        );

        // A second callback to check if multipla callbacks can be used.
        $client->before(function () use (&$called2) {
            $called2 = TRUE;
        });

        $client->callBeforeCallbacks();

        // Checking if all callbacks were invoked.
        $this->assertTrue($called1);
        $this->assertTrue($called2);
    }

    /**
     * @covers \Pakard\RestClient\RestClient::after()
     * @covers \Pakard\RestClient\RestClient::callAfterCallbacks()
     */
    public function testAfter() {
        $called = FALSE;
        $called2 = FALSE;

        // Testing with no callback set, also testing caller return value.
        $this->assertSame(
            $client = new RestClient,
            $client->callAfterCallbacks()
        );

        // Testing setter return value (should be the same RestClient instance).
        // Preparing callback to be able to check if client is the same.
        $this->assertSame(
            $client,
            $client->after(
                function ($argument) use ($client, &$called) {
                    $this->assertSame($client, $argument);
                    $called = TRUE;
                }
            )
        );

        // A second callback to check if multipla callbacks can be used.
        $client->after(function () use (&$called2) {
            $called2 = TRUE;
        });

        $client->callAfterCallbacks();

        // Checking if all callbacks were invoked.
        $this->assertTrue($called);
        $this->assertTrue($called2);
    }

    /**
     * @covers \Pakard\RestClient\RestClient::send()
     */
    public function testSend() {
        $request = NULL;
        $response = NULL;

        // Setting up client instance.
        $client = (new RestClient)
            ->setBaseUrl('http://example.com')
            ->setTransport(
                (new MockTransport)
                    ->setCallback(
                        function (RequestInterface $req, ResponseInterface $res) use (&$request, &$response) {
                            $request = $req;
                            $response = $res;

                            // Checking if request was prepared properly.
                            $this->assertSame(RequestInterface::METHOD_POST, $req->getMethod());
                            $this->assertSame('http://example.com/test/url', $req->getUrl());
                            $this->assertSame(['field1' => 'value1'], $req->getBody());
                            $this->assertSame('testvalue', $req->getHeader('Test-Header'));

                            // Setting response.
                            $res->setStatusCode(200);
                            $res->setContentType('application/json; charset=UTF-8');
                            $res->setRawBody('{"msg":"This is a JSON response."}');
                        }
                    )
            )
            ->before(
                function (RestClient $client) use (&$request, &$response) {
                    // Request and response didn't reach the MockTransport
                    // callback yet.
                    $this->assertNull($request);
                    $this->assertNull($response);

                    // Setting a header to check in the MockTransport callback.
                    $client->getRequest()->addHeader('Test-Header', 'testvalue');
                }
            )
            ->after(
                function (RestClient $client) use (&$request, &$response) {
                    // Request and response objects already should be set
                    // from MockTransport callback.
                    $this->assertSame($request, $client->getRequest());
                    $this->assertSame($response, $client->getResponse());
                }
            );

        // Sending request.
        $this->assertEquals(
            (object) ['msg' => 'This is a JSON response.'],
            $client->send(
                RequestInterface::METHOD_POST,
                'test/url',
                ['field1' => 'value1']
            )
        );
    }

    /**
     * @covers \Pakard\RestClient\RestClient::send()
     * @expectedException LogicException
     */
    public function testSendWithoutTransport() {
        (new RestClient)->send(RequestInterface::METHOD_GET, 'test');
    }

    /**
     * @covers \Pakard\RestClient\RestClient::send()
     * @expectedException Pakard\RestClient\StatusCodeException
     */
    public function testSendErrorStatus() {
        (new RestClient)
            ->setTransport(
                (new MockTransport)
                    ->setCallback(
                        function (RequestInterface $req, ResponseInterface $res) {
                            $res->setStatusCode(ResponseInterface::STATUS_CODE_NOT_FOUND);
                        }
                    )
            )
            ->send(RequestInterface::METHOD_POST, 'url');
    }
}
