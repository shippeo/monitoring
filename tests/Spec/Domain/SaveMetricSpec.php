<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Database\DatabaseIterator;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\SaveMetric;

class SaveMetricSpec extends ObjectBehavior
{
    function let(Database $database1, Database $database2)
    {
        $this->beConstructedWith(
            new DatabaseIterator(
                [
                    $database1->getWrappedObject(),
                    $database2->getWrappedObject(),
                ]
            )
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SaveMetric::class);
    }

    function it_adds_metric_to_every_known_database(Database $database1, Database $database2, Metric $metric)
    {
        $database1->add($metric)->shouldBeCalled();
        $database2->add($metric)->shouldBeCalled();

        $this->__invoke($metric);
    }
}
