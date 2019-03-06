<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Database\StatsD;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\Database\StatsD\Client;
use Shippeo\Heimdall\Application\Database\StatsD\Key;
use Shippeo\Heimdall\Application\Database\StatsD\StatsD;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Gauge;
use Shippeo\Heimdall\Domain\Metric\Metric;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Timer;

final class StatsDSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StatsD::class);
    }

    function it_implements_Database()
    {
        $this->shouldImplement(Database::class);
    }

    function it_throws_a_logic_exception_if_metric_is_not_supported(Metric $metric)
    {
        $metric->key()->willReturn('fakeKey');
        $metric->tags()->willReturn(new TagIterator([]));

        $this
            ->shouldThrow(\LogicException::class)
            ->during('store', [$metric])
        ;
    }

    function it_stores_a_counter_metric(Client $client)
    {
        $metric = new Counter('fakeKey', 1, new TagIterator([new Tag()]));

        $client
            ->increment(
                Argument::exact(new Key($metric->key(), $metric->tags())),
                $metric->value()
            )
            ->shouldBeCalledOnce()
        ;

        $this->store($metric);
    }

    function it_stores_a_timer_metric(Client $client)
    {
        $metric = new Timer('fakeKey', Timer\Time::now(), Timer\Time::now(), new TagIterator([new Tag()]));

        $client
            ->timing(
                Argument::exact(new Key($metric->key(), $metric->tags())),
                $metric->value()
            )
            ->shouldBeCalledOnce()
        ;

        $this->store($metric);
    }

    function it_stores_a_gauge_metric(Client $client)
    {
        $metric = new Gauge('fakeKey', 1, new TagIterator([new Tag()]));

        $client
            ->gauge(
                Argument::exact(new Key($metric->key(), $metric->tags())),
                $metric->value()
            )
            ->shouldBeCalledOnce()
        ;

        $this->store($metric);
    }
}
