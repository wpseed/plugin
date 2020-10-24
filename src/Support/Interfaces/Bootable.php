<?php

namespace Wpseed\Support\Interfaces;

use Closure;

interface Bootable
{
    /**
     * @param Closure $boot
     * @return mixed
     */
    public function boot( Closure $boot );
}