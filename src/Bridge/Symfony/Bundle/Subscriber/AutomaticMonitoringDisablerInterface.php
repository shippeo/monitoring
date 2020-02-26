<?php

declare(strict_types=1);

namespace Shippeo\Heimdall\Bridge\Symfony\Bundle\Subscriber;

interface AutomaticMonitoringDisablerInterface
{
    public function isAutomaticMonitoringDisabled(): bool;
}
