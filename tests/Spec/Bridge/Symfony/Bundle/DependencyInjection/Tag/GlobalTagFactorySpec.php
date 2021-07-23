<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Application\Metric\Tag\TagCollection;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\DependencyInjection\Tag\GlobalTagFactory;
use Shippeo\Heimdall\Bridge\Symfony\Bundle\Metric\Tag\GlobalTag;

class GlobalTagFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GlobalTagFactory::class);
    }

    function it_creates_a_tag_collection()
    {
        $this::create([])->shouldBeLike(new TagCollection([]));
    }

    function it_creates_a_tag_collection_with_provided_tags()
    {
        $tagName1 = 'fakeName1';
        $tagName2 = 'fakeName2';
        $tagValue1 = 'fakeValue1';
        $tagValue2 = 2;

        $this::create(
            [
                $tagName1 => $tagValue1,
                $tagName2 => $tagValue2,
            ]
        )
            ->shouldBeLike(
                new TagCollection(
                    [
                        new GlobalTag($tagName1, $tagValue1),
                        new GlobalTag($tagName2, (string) $tagValue2),
                    ]
                )
            )
        ;
    }

    function it_creates_a_tag_colleciton_with_named_tags_only()
    {
        $tagName1 = 'fakeName1';
        $tagValue1 = 'fakeValue1';

        $this::create(
            [
                'value0',
                '' => 'value1',
                12 => 'value2',
                $tagName1 => $tagValue1,
            ]
        )
            ->shouldBeLike(
                new TagCollection(
                    [
                        new GlobalTag($tagName1, $tagValue1),
                    ]
                )
            )
        ;
    }
}
