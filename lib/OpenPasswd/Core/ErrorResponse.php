<?php

namespace OpenPasswd\Core;

class ErrorResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    /**
     * Constructor.
     *
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     */
    public function __construct($data = null, $status = 500, $headers = array())
    {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        } elseif (is_string($data) === true) {
            $data = array(
                'description' => $data,
                'error' => 404 === $status ? 'Item not found' : null,
            );
        }
        $this->setData($data);
    }
}