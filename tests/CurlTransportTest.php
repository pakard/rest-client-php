<?php

/**
 * Tests for {@see Pakard\RestClient\CurlTransport} class.
 */
class CurlTransportTest extends PHPUnit\Framework\TestCase {
    /**
     * Provides CURL options with values of different types.
     * @return array
     */
    public function optionDataProvider() {
        return [
            'int' => [CURLOPT_CONNECTTIMEOUT, 10],
            'array' => [CURLOPT_HEADERFUNCTION, [$this, 'noSuchMethod']],
            'null' => [CURLOPT_HEADERFUNCTION, NULL],
            'bool' => [CURLOPT_RETURNTRANSFER, TRUE],
            'string' => [CURLOPT_SSL_CIPHER_LIST, 'TLSv1'],
        ];
    }

    /**
     * Tests <tt>getOption()</tt> and <tt>setOption()</tt> methods together:
     * return values, transitivity, type-safety.
     *
     * @param int $option
     * @param mixed $value
     * @dataProvider optionDataProvider
     */
    public function testGetSetOption($option, $value) {
        $this->assertSame(
            $value,
            (new \Pakard\RestClient\CurlTransport)
                ->setOption($option, $value)
                ->getOption($option)
        );
    }
}
