<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric;

use Fake\Tag;
use Fake\Template as FakeTemplate;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Factory;
use Shippeo\Heimdall\Domain\Metric\Gauge;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Tag\NullTag;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;
use Shippeo\Heimdall\Domain\Metric\Template;
use Shippeo\Heimdall\Domain\Metric\Timer;

final class FactorySpec extends ObjectBehavior
{
    /** @var Tag */
    private $globalTag1;

    function let()
    {
        $this->globalTag1 = new Tag();

        $this->beConstructedWith(new TagIterator([$this->globalTag1]));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_throws_an_exception_when_template_is_not_mapped(Template\Template $template)
    {
        $template->tags()->willReturn(new NameIterator([]));

        $this
            ->shouldThrow(\LogicException::class)
            ->during('create', [$template, new TagIterator([new Tag\Tag1(), new Tag\Tag2()])])
        ;
    }

    function it_creates_a_counter_metric()
    {
        $template = new FakeTemplate\Counter();
        $tag1 = new Tag\Tag1();
        $tag2 = new Tag\Tag2();

        $this
            ->create($template, new TagIterator([$tag1, $tag2]))
            ->shouldBeLike(
                new Counter(
                    $template->name(),
                    $template->value(),
                    new TagIterator(
                        [
                            $tag1,
                            $tag2,
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }

    function it_creates_a_counter_metric_without_all_tags()
    {
        $template = new FakeTemplate\Counter();
        $tag1 = new Tag\Tag1();

        $this
            ->create($template, new TagIterator([$tag1]))
            ->shouldBeLike(
                new Counter(
                    $template->name(),
                    $template->value(),
                    new TagIterator(
                        [
                            $tag1,
                            new NullTag((new Tag\Tag2())->name()),
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }

    function it_creates_a_timer_metric()
    {
        $template = new FakeTemplate\Timer();
        $tag1 = new Tag\Tag1();
        $tag2 = new Tag\Tag2();

        $this
            ->create($template, new TagIterator([$tag1, $tag2]))
            ->shouldBeLike(
                new Timer(
                    $template->name(),
                    $template->start(),
                    $template->end(),
                    new TagIterator(
                        [
                            $tag1,
                            $tag2,
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }

    function it_creates_a_timer_metric_without_all_tags()
    {
        $template = new FakeTemplate\Timer();
        $tag1 = new Tag\Tag1();

        $this
            ->create($template, new TagIterator([$tag1]))
            ->shouldBeLike(
                new Timer(
                    $template->name(),
                    $template->start(),
                    $template->end(),
                    new TagIterator(
                        [
                            $tag1,
                            new NullTag((new Tag\Tag2())->name()),
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }

    function it_creates_a_gauge_metric()
    {
        $template = new FakeTemplate\Gauge();
        $tag1 = new Tag\Tag1();
        $tag2 = new Tag\Tag2();

        $this
            ->create($template, new TagIterator([$tag1, $tag2]))
            ->shouldBeLike(
                new Gauge(
                    $template->name(),
                    $template->value(),
                    new TagIterator(
                        [
                            $tag1,
                            $tag2,
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }

    function it_creates_a_gauge_metric_without_all_tags()
    {
        $template = new FakeTemplate\Gauge();
        $tag1 = new Tag\Tag1();

        $this
            ->create($template, new TagIterator([$tag1]))
            ->shouldBeLike(
                new Gauge(
                    $template->name(),
                    $template->value(),
                    new TagIterator(
                        [
                            $tag1,
                            new NullTag((new Tag\Tag2())->name()),
                            $this->globalTag1,
                        ]
                    )
                )
            )
        ;
    }
}
