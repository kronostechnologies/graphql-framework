<?php


namespace Kronos\GraphQLFramework\Relay;


use function array_key_exists;
use Kronos\GraphQLFramework\FrameworkMiddleware;

class RelayMiddleware implements FrameworkMiddleware
{
    /**
     * @var string
     */
    protected $idFieldName;

    /**
     * @param string $idFieldName
     */
    public function __construct($idFieldName)
    {
        $this->idFieldName = $idFieldName;
    }

    /**
     * @param array|mixed $request
     * @return array|mixed
     * @throws Exception\InvalidPayloadException
     */
    public function modifyRequest($request)
    {
        if (is_array($request)) {
            if (array_key_exists($this->idFieldName, $request)) {
                $relayIdentifier = new RelayGlobalIdentifier();
                $relayIdentifier->deserialize($request[$this->idFieldName]);

                $request[$this->idFieldName] = $relayIdentifier->getIdentifier();
            }

            foreach ($request as $key => $value) {
                if (is_array($value)) {
                    $request[$key] = $this->modifyRequest($value);
                }
            }

            return $request;
        } else {
            return $request;
        }
    }

    /**
     * @param \stdClass|mixed $response
     * @return \stdClass|mixed
     */
    public function modifyResponse($response)
    {
        // Is request object or something else?
        // > Object:
        //   Reflect properties contained in class
        //   If class contains id property, set its value to RelayCursor(entityName: ClassFQN, id)
        //   > Enumerate each property k/v:
        //      If value contains array or object, return to execution start
        // > Something else:
        //   Ignore
    }
}
