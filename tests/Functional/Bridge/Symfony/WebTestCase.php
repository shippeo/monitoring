<?php

declare(strict_types=1);

namespace Functional\Bridge\Symfony;

use Functional\Fake\Bridge\Symfony\Kernel;
use Psr\Container\ContainerInterface;

abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected static function createKernel(array $options = [])
    {
        return new Kernel();
    }

    protected function container(): ContainerInterface
    {
        if (static::$kernel === null) {
            static::bootKernel();
        }

        return static::$kernel->getContainer();
    }
}
