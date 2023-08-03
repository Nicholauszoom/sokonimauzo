<?php

namespace App\Tests;

use App\Entity\Messages;
use Container3X7x3U0\getMessagesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UtilTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(getMessagesService::class);
    }
}
