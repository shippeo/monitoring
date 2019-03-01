<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Template;

use Shippeo\Heimdall\Domain\Metric\Timer\Time;

interface Timer extends Template
{
    public function start(): Time;

    public function end(): Time;
}
