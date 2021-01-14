<?php
namespace App\Contract\Auth;

interface Factory
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param  string  $driver
     * @return \App\Http\Controllers\Auth\Provider\ProviderAbstract
     */
    public function driver($driver = null);
}
