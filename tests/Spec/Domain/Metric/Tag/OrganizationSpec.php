<?php

declare(strict_types=1);

namespace Spec\Shippeo\Heimdall\Domain\Metric\Tag;

use PhpSpec\ObjectBehavior;
use Shippeo\Heimdall\Domain\Metric\Tag\Name;
use Shippeo\Heimdall\Domain\Metric\Tag\Organization;
use Shippeo\Heimdall\Domain\Metric\Tag\Tag;
use Shippeo\Heimdall\Domain\Model\Identifier\OrganizationId;

final class OrganizationSpec extends ObjectBehavior
{
    /** @var OrganizationId */
    private $organizationId;

    function let()
    {
        $organization = new \Fake\Organization();
        $this->organizationId = $organization->id();

        $this->beConstructedWith($this->organizationId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Organization::class);
    }

    function it_implements_Tag()
    {
        $this->shouldImplement(Tag::class);
    }

    function it_returns_the_expected_name()
    {
        $this->name()->shouldBeLike(new Name('organization'));
    }

    function it_returns_the_expected_value()
    {
        $this->value()->shouldBe((string) $this->organizationId);
    }

    function it_returns_an_empty_value_when_initialized_with_null()
    {
        $this->beConstructedWith(null);

        $this->value()->shouldBe('');
    }
}
