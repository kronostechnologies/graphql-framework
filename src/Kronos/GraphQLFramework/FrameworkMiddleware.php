<?php


namespace Kronos\GraphQLFramework;


interface FrameworkMiddleware
{
    /**
     * @param array|\stdClass|mixed $request
     * @return array|\stdClass|mixed
     */
    public function modifyRequest($request);

    /**
     * @param array|\stdClass|mixed $response
     * @return array|\stdClass|mixed
     */
    public function modifyResponse($response);
}
