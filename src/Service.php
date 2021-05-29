<?php

namespace ycl123\queue;

use think\Service as thinkService;

class Service extends thinkService
{
    public function boot(): void
    {
        $this->commands([Queue::class]);
    }
}
