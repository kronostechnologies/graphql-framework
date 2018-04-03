<?php

/**
 * Defines an OAuth authenticated user. Boilerplate, incomplete code mostly, as it is out of scope for the moment.
 */
class OAuthSessionContext extends SessionContext
{
    /**
     * Gets the webuser ID, or null if the session context is not authenticated yet.
     * @return string|null
     */
    public function getId()
    {

    }

    /**
     * Not a system service.
     * @return bool
     */
    public function isSystemService()
    {
        return false;
    }

    /**
     * Is a user.
     * @return bool
     */
    public function isUser()
    {
        return true;
    }

    /**
     * If is considered authenticated with OAuth.
     * @return bool
     */
    public function isAuthenticated()
    {

    }
}