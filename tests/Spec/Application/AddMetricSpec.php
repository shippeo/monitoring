<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application;

use Fake\Tag;
use Fake\Template as FakeTemplate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shippeo\Heimdall\Application\AddMetric;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Domain\Database\Database;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag\NameIterator;
use Shippeo\Heimdall\Domain\Metric\Template\Template;

final class AddMetricSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([], new TagCollection([]));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AddMetric::class);
    }

    function it_invokes_save_metric_with_a_counter(Database $database)
    {
        $template = new FakeTemplate\Counter();
        $tags = new TagCollection([new Tag\Tag1(), new Tag\Tag2()]);

        $this->beConstructedWith([$database->getWrappedObject()], new TagCollection([]));

        $database
            ->store(
                Argument::exact(
                    new Counter($template->name(), $template->value(), $tags->getIterator())
                )
            )
            ->shouldBeCalled()
        ;

        $this->__invoke($template, $tags);
    }

    function it_invokes_save_metric_with_global_tags(Database $database)
    {
        $template = new FakeTemplate\Counter();
        $tags = new TagCollection([new Tag\Tag1(), new Tag\Tag2()]);

        $globalTags = new TagCollection([new Tag()]);
        $this->beConstructedWith([$database->getWrappedObject()], $globalTags);

        $database
            ->store(
                Argument::exact(
                    new Counter(
                        $template->name(),
                        $template->value(),
                        $tags->mergeWith($globalTags)->getIterator()
                    )
                )
            )
            ->shouldBeCalled()
        ;

        $this->__invoke($template, $tags);
    }

    function it_throws_an_exception_when_template_is_not_mapped(Template $template)
    {
        $template->tags()->willReturn(new NameIterator([]));

        $this->shouldThrow(\LogicException::class)->during('__invoke', [$template, new TagCollection([])]);
    }
}
