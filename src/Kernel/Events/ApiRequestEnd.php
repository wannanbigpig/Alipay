<?php

namespace WannanBigPig\Alipay\Kernel\Events;

class ApiRequestEnd extends Event
{
    /**
     * NAME
     *
     * @var string
     */
    const NAME = 'api_request_end';

    /**
     * request.
     *
     * @var array | string
     */
    public $request;

    /**
     * result.
     *
     * @var array | string
     */
    public $result;

    /**
     * uri.
     *
     * @var string
     */
    public $uri;

    /**
     * ApiRequestEnd constructor.
     *
     * @param  string|null  $driver
     * @param  string|null  $method
     * @param  string  $uri
     * @param          $request
     * @param          $result
     */
    public function __construct($driver, $method, string $uri, $request, $result)
    {
        $this->request = $request;
        $this->uri = $uri;
        $this->result = $result;

        parent::__construct($driver, $method);
    }

    /**
     * getRequest
     *
     * @return array | string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * getResult
     *
     * @return array | string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * getUri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
