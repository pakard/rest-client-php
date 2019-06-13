<?php
namespace Pakard\RestClient;

/**
 *
 */
class CurlTransport implements TransportInterface {
    /**
     * @var array
     */
    protected $_options = [];

    /**
     * @param int $option
     * @return mixed
     */
    public function getOption($option) {
        if (isset($this->_options[$option])) {
            return $this->_options[$option];
        }

        return NULL;
    }

    /**
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value) {
        $this->_options[$option] = $value;
        return $this;
    }

    /**
     * @param \Pakard\RestClient\RequestInterface $request
     * @param \Pakard\RestClient\ResponseInterface $response
     * @return \Pakard\RestClient\ResponseInterface
     * @throws \Pakard\RestClient\TransportException
     */
    public function send(RequestInterface $request, ResponseInterface $response) {
        $ch = curl_init();

        // If the PHP7 SSL constant is defined, going with that; otherwise, using the
        // deprecated SSL setting.
        if (defined(CURL_SSLVERSION_TLSv1)) {
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        } else {
            curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
        }

        // Applying custom options to resource.
        // Using a foreach loop to make sure remaining options are applied in
        // case of an errorous option/value somewhere in between.
        foreach ($this->_options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        // Returning response string instead of putting it out directly.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Recording request headers too.
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

        // Setting request extra headers.
        $headers = [];

        foreach ($request->getHeaders() as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }

        if (count($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Collecting response header.
        $headers = '';
        curl_setopt(
            $ch,
            CURLOPT_HEADERFUNCTION,
            function ($resource, $string) use (&$headers) {
                $headers .= $string;
                return strlen($string);
            }
        );

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($ch, CURLOPT_URL, $request->getUrl());

        if (Request::isBodyAllowed($request->getMethod())) {
            $request->setBodySent($body = $request->encodeBody());
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            if ($this->getOption(CURLOPT_POST) === NULL) {
                curl_setopt($ch, CURLOPT_POST, TRUE);
            }
        }

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);

        $response
            ->setStatusCode($info['http_code'])
            ->setContentType($info['content_type'])
            ->setElapsedTime($info['total_time'])
            ->setRawHeaders($headers)
            ->setRawBody($result);

        if (isset($info['request_header'])) {
            $request->setHeadersSent($info['request_header']);
        }

        if ($errno) {
            throw new TransportException($errmsg, $errno);
        }

        return $response;
    }
}
