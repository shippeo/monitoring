<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Metric;

final class AddMetricSpec extends ObjectBehavior
{
    function let(Factory $factory)
    {
        $this->beConstructedWith([], $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddMetric::class);
    }

    function it_invokes_save_metric(
        Database $database,
        Factory $factory,
        Template $template,
        Metric $metric
    ) {
        $this->beConstructedWith([$database->getWrappedObject()], $factory);

        $factory->create($template)->willReturn($metric);
        $database->store($metric)->shouldBeCalled();

        $this->__invoke($template);
    }
}
