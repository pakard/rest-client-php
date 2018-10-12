<?php
use Pakard\RestClient\Request;
use Pakard\RestClient\RequestInterface;

/**
 *
 */
class RequestTest extends PHPUnit\Framework\TestCase {
    /**
     *
     */
    public function testInheritance() {
        $this->assertInstanceOf('Pakard\RestClient\RequestInterface', new Request);
    }

    /**
     *
     */
    public function testMethod() {
        // Testing default value;
        $this->assertSame(RequestInterface::METHOD_GET, (new Request)->getMethod());

        // Testing method chainability.
        $this->assertSame($request = new Request, $request->setMethod('POST'));

        // Testing method transitivity.
        $this->assertSame('POST', (new Request)->setMethod('POST')->getMethod());
    }

    /**
     *
     */
    public function testUrl() {
        // Testing default value;
        $this->assertNull((new Request)->getUrl());

        // Testing method chainability.
        $this->assertSame($request = new Request, $request->setUrl('testurl'));

        // Testing method transitivity.
        $this->assertSame('testurl', (new Request)->setUrl('testurl')->getUrl());
    }

    /**
     *
     */
    public function testBody() {
        // Testing default value;
        $this->assertNull((new Request)->getBody());

        // Testing method chainability.
        $this->assertSame($request = new Request, $request->setBody('testvalue'));

        // Testing method transitivity.
        $this->assertSame('testvalue', (new Request)->setBody('testvalue')->getBody());

        // Testing with non-string values.
        $this->assertSame(25, (new Request)->setBody(25)->getBody());
        $this->assertSame(['one'], (new Request)->setBody(['one'])->getBody());
        $this->assertSame(
            $object = (object) ['two' => 2],
            (new Request)->setBody($object)->getBody()
        );
    }

    /**
     *
     */
    public function testEncodeBody() {
        // Testing default.
        $this->assertSame('', (new Request)->encodeBody());
        $this->assertSame(
            'one=1&two=2',
            (new Request)->setBody(['one' => 1, 'two' => 2])->encodeBody()
        );

        // Testing explicit default.
        $this->assertSame(
            '',
            (new Request)
                ->addHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->encodeBody()
        );
        $this->assertSame(
            'one=1&two=2',
            (new Request)
                ->addHeader('Content-Type', 'application/x-www-form-urlencoded')
                ->setBody((object) ['one' => 1, 'two' => 2])
                ->encodeBody()
        );

        // Testing JSON encode.
        $this->assertNull(
            (new Request)
                ->addHeader('Content-Type', 'application/json; charset=UTF-8')
                ->encodeBody()
        );
        $this->assertSame(
            '{"one":1,"two":2}',
            (new Request)
                ->addHeader('Content-Type', 'application/json; charset=UTF-8')
                ->setBody(['one' => 1, 'two' => 2])
                ->encodeBody()
        );
    }

    /**
     *
     */
    public function testHeaders() {
        // Testing default value.
        $this->assertSame([], (new Request)->getHeaders());

        // Testing non-existant header name.
        $this->assertNull((new Request)->getHeader('Content-Type'));

        // Testing setHeader() return value.
        $this->assertSame(
            $request = new Request,
            $request->addHeader('Content-Type', 'application/json')
        );

        // Testing setHeader()/getHeader() transitivity.
        $this->assertSame(
            'application/json; charset=UTF-8',
            (new Request)
                ->addHeader('Content-Type', 'application/json; charset=UTF-8')
                ->getHeader('Content-Type')
        );

        // Testing getHeaders() return value once more.
        $this->assertSame(
            ['one' => 1, 'two' => 2],
            (new Request)->addHeader('one', 1)->addHeader('two', 2)->getHeaders()
        );
    }

    /**
     *
     */
    public function testHeadersSent() {
        // Testing default value;
        $this->assertNull((new Request)->getHeadersSent());

        // Testing method chainability.
        $this->assertSame($request = new Request, $request->setHeadersSent('testvalue'));

        // Testing method transitivity.
        $this->assertSame('testvalue', (new Request)->setHeadersSent('testvalue')->getHeadersSent());
    }

    /**
     *
     */
    public function testBodySent() {
        // Testing default value;
        $this->assertNull((new Request)->getBodySent());

        // Testing method chainability.
        $this->assertSame($request = new Request, $request->setBodySent('testvalue'));

        // Testing method transitivity.
        $this->assertSame('testvalue', (new Request)->setBodySent('testvalue')->getBodySent());
    }

    /**
     * @return array
     */
    public function methodDataProvider() {
        return [
            'POST' => ['POST', TRUE],
            'PUT' => ['PUT', TRUE],
            'post' => ['post', TRUE],
            'put' => ['put', TRUE]
        ];
    }

    /**
     * @param string $method
     * @param bool $expected
     * @dataProvider methodDataProvider
     */
    public function testIsBodyAllowed($method, $expected) {
        $this->assertSame($expected, Request::isBodyAllowed($method));
    }
}
