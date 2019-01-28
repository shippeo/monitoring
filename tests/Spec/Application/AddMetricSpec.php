<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\SaveMetric;

final class AddMetricSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new SaveMetric(new DatabaseIterator([])));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddMetric::class);
    }

    function it_invokes_save_metric(Database $database, Metric $metric)
    {
        $this->beConstructedWith(new SaveMetric(new DatabaseIterator([$database->getWrappedObject()])));

        $database->add($metric)->shouldBeCalled();

        $this->__invoke($metric);
    }
}
