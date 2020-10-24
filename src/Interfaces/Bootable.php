<?php

namespace Wpseed\Interfaces;

use Closure;

interface Bootable
{
    /**
     * @param Closure $boot
     * @return mixed
     */
    public function boot( Closure $boot );
}