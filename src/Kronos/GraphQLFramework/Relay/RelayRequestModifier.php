<?php


namespace Kronos\GraphQLFramework\Relay;


class RelayRequestModifier
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
     * @param array|\stdClass|mixed $request
     */
    public function getRawIdFromRequest($request)
    {
        // Is request array, object, or something else?
        // > Array:
        //   If key named id is found, set field from RelayCursor(entityName: ClassFQN, id).id
        //   > Enumerate each array k/v:
        //      If value contains array or object, return to execution start
        // > Object:
        //   Reflect properties contained in class
        //   If class contains id property, set field from RelayCursor(entityName: ClassFQN, id).id
        //   > Enumerate each property k/v:
        //      If value contains array or object, return to execution start
        // > Something else:
        //   Ignore
    }

    /**
     * @param array|\stdClass|mixed $response
     */
    public function setRelayIdForResponse($response)
    {
        // Is request array, object, or something else?
        // > Array:
        //   If key named id is found, construct RelayCursor(entityName: ClassFQN, id)
        //   > Enumerate each array k/v:
        //      If value contains array or object, return to execution start
        // > Object:
        //   Reflect properties contained in class
        //   If class contains id property, set its value to RelayCursor(entityName: ClassFQN, id)
        //   > Enumerate each property k/v:
        //      If value contains array or object, return to execution start
        // > Something else:
        //   Ignore
    }
}
