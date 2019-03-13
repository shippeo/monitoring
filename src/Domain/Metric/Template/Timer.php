<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Domain\Metric\Template;

use Shippeo\Heimdall\Domain\Metric\Timer\Duration;

interface Timer extends Template
{
    public function duration(): Duration;
}
