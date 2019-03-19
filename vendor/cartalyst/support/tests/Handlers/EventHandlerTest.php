<?php

/**
 * Part of the Support package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Support
 * @version    2.0.1
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Support\Tests\Handlers;

use PHPUnit_Framework_TestCase;
use Illuminate\Container\Container;
use Cartalyst\Support\Handlers\EventHandler;

class EventHandlerTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_can_be_instantiated()
    {
        new EventHandlerStub(new Container);
    }

    /** @test */
    public function it_can_retrieve_dynamic_objects_from_the_container()
    {
        $container = new Container;
        $container->bind('foo', function () { return 'bar'; });

        $handler = new EventHandlerStub($container);

        $this->assertSame('bar', $handler->foo);
    }
}

class EventHandlerStub extends EventHandler
{
}
