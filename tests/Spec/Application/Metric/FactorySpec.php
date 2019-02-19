<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Application\Metric;

use Fake\Tag;
use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Factory;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Application\Metric\Template\Template;
use Shippeo\Heimdall\Domain\Metric\Counter;
use Shippeo\Heimdall\Domain\Metric\Tag\TagIterator;

final class FactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new TagCollection([]));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_throws_a_LogicException_when_template_can_not_be_handled(Template $template)
    {
        $type = 'notAValidType';

        $template->type()->willReturn($type);

        $this
            ->shouldThrow(new \LogicException($type.' is not handled.'))
            ->during('create', [$template])
        ;
    }

    function it_creates_a_Counter_metric(Template $template)
    {
        $name = 'fakeName';
        $value = 3;

        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag3 = new Tag();
        $tag4 = new Tag();

        $this->beConstructedWith(
            new TagCollection(
                [
                    $tag3,
                    $tag4,
                ]
            )
        );

        $template->type()->willReturn(Counter::class);
        $template->name()->willReturn($name);
        $template->value()->willReturn($value);
        $template
            ->tags()
            ->willReturn(
                new TagCollection(
                    [
                        $tag1,
                        $tag2,
                    ]
                )
            )
        ;

        $this
            ->create($template)
            ->shouldBeLike(
                new Counter(
                    $name,
                    $value,
                    new TagIterator(
                        [
                            $tag1,
                            $tag2,
                            $tag3,
                            $tag4,
                        ]
                    )
                )
            )
        ;
    }
}
