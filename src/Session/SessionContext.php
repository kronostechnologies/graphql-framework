<?php

/**
 * Defines a basic authentication context. It should be able to tell if the context is a system service
 * or a live user by itself. Boilerplate, incomplete code mostly, as it is out of scope for the moment.
 */
abstract class SessionContext
{
    public abstract function isAuthenticated();

    public abstract function isSystemService();

    public abstract function isUser();
}