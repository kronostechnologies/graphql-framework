<?php

/**
 * An unauthenticated context. Should only be used for pre-auth contexts.
 */
class GuestSessionContext
{
    /**
     * Absolutely never ever think about making this a system service.
     * @return bool
     */
    public function isSystemService()
    {
        return false;
    }

    /**
     * Not a user either.
     * @return bool
     */
    public function isUser()
    {
        return false;
    }

    /**
     * Authenticated fine.
     */
    public function isAuthenticated()
    {
        return true;
    }
}