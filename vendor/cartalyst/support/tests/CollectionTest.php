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

namespace Cartalyst\Support\Tests;

use PHPUnit_Framework_TestCase;
use Cartalyst\Support\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function a_collection_can_be_instantiated()
    {
        $collection = new Collection('main');
    }

    /** @test */
    public function it_can_get_all_the_items_from_the_collection()
    {
        $collection = new Collection('main');
        $this->assertEmpty($collection->all());

        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');
        $this->assertEquals([
            'foo' => 'Foo',
            'bar' => 'Bar',
        ], $collection->all());
    }

    /** @test */
    public function it_can_get_the_total_items_from_the_collection()
    {
        $collection = new Collection('main');
        $this->assertCount(0, $collection);

        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');
        $this->assertCount(2, $collection);
    }

    /** @test */
    public function it_can_check_an_item_exists_on_the_collection()
    {
        $collection = new Collection('main');
        $this->assertFalse($collection->exists('foo'));

        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');
        $this->assertTrue($collection->exists('foo'));
    }

    /** @test */
    public function it_can_find_an_item_from_the_collection()
    {
        $collection = new Collection('main');
        $this->assertNull($collection->find('foo'));

        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');
        $this->assertEquals('Foo', $collection->find('foo'));
    }

    /** @test */
    public function it_can_return_the_first_item_from_the_collection()
    {
        $collection = new Collection('main');
        $collection->put('name', 'Bar');

        $this->assertEquals('Bar', $collection->first());
    }

    /** @test */
    public function it_can_get_a_collection_attribute()
    {
        $collection = new Collection('main');
        $this->assertEquals(null, $collection->get('foo'));
        $collection->foo = 'Foo';
        $this->assertEquals('Foo', $collection->get('foo'));
    }

    /** @test */
    public function it_can_get_all_the_attributes_from_the_collection()
    {
        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->name = 'Foo';

        $this->assertEquals([
            'id'   => 'main',
            'name' => 'Foo',
        ], $collection->getAttributes());
    }

    /** @test */
    public function it_can_check_if_the_collection_has_an_item()
    {
        $collection = new Collection('main');
        $this->assertFalse($collection->has('foo'));
        $collection->put('foo', 'Foo');
        $this->assertTrue($collection->has('foo'));
    }

    /** @test */
    public function it_can_check_that_a_collection_is_empty()
    {
        $collection = new Collection('main');

        $this->assertTrue($collection->isEmpty());
    }

    /** @test */
    public function it_can_check_that_a_collection_is_not_empty()
    {
        $collection = new Collection('main');
        $collection->put('foo', 'Foo');

        $this->assertFalse($collection->isEmpty());
    }

    /** @test */
    public function it_can_return_the_last_item_from_the_collection()
    {
        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');

        $this->assertEquals('Bar', $collection->last());
    }

    /** @test */
    public function it_can_retrieve_the_collection_items_as_an_array()
    {
        $collection = new Collection('main');
        $this->assertEquals(null, $collection->get('foo'));
        $this->assertEquals([
            'id' => 'main',
        ], $collection->getAttributes());
        $collection->put('foo', 'Foo');
        $this->assertEquals(['foo' => 'Foo'], $collection->toArray());
    }

    /** @test */
    public function it_can_pull_an_item_from_the_collection()
    {
        $collection = new Collection('main');
        $collection->put('foo', 'Foo');
        $collection->put('bar', 'Bar');
        $collection->put('baz', 'Baz');
        $collection->put('bat', 'Bat');

        $this->assertCount(4, $collection);

        $collection->pull('bar');

        $this->assertCount(3, $collection);
    }

    /** @test */
    public function it_can_test_the_offset_methods()
    {
        $collection = new Collection('main');
        $collection['name'] = 'Foo';
        $this->assertTrue(isset($collection['name']));
        $this->assertEquals('Foo', $collection['name']);
        unset($collection['name']);
        $this->assertFalse(isset($collection['name']));


        $collection = new Collection('main');
        $collection->name = 'Foo';
        $this->assertTrue(isset($collection->name));
        unset($collection->name);
        $this->assertFalse(isset($collection->name));


        $collection = new Collection('main');
        $collection->put('foo.bar', 'baz');
        $this->assertEquals('baz', $collection['foo.bar']);
        unset($collection['foo.bar']);
        $this->assertFalse($collection->exists('foo.bar'));


        $collection = new Collection('main');
        $collection->put('foo.bar.baz', 'bat');
        $this->assertEquals('bat', $collection['foo.bar.baz']);
        $this->assertTrue($collection->exists('foo.bar.baz'));
    }

    /** @test */
    public function a_collection_can_have_attributes()
    {
        $collection = new CollectionStub('main');
        $collection->name = 'Foo';

        $this->assertEquals('Test Foo', $collection->name);
    }

    /** @test */
    public function it_can_use_magic_methods_to_set_items()
    {
        $collection = new Collection('main');
        $collection->id = 'foo';
        $this->assertEquals('foo', $collection->id);

        $collection = new Collection('main');
        $collection->id = 'foo';
        $this->assertEquals('foo', $collection->id);

        $collection = new Collection('main', function ($collection) {
            $collection->id = 'foo';
        });
        $this->assertEquals('foo', $collection->id);
    }

    /** @test */
    public function it_can_sort_the_collection_items()
    {
        $collection = new Collection('main');
        $collection->put('foo', ['id' => 1, 'name' => 'Foo']);
        $collection->put('bar', ['id' => 2, 'name' => 'Bar']);
        $collection->put('baz', ['id' => 3, 'name' => 'Baz']);

        $this->assertEquals([
            'foo' => [
                'id'   => 1,
                'name' => 'Foo',
            ],
            'bar' => [
                'id'   => 2,
                'name' => 'Bar',
            ],
            'baz' => [
                'id'   => 3,
                'name' => 'Baz',
            ],
        ], $collection->all());

        $this->assertEquals([
            'bar' => [
                'id'   => 2,
                'name' => 'Bar',
            ],
            'baz' => [
                'id'   => 3,
                'name' => 'Baz',
            ],
            'foo' => [
                'id'   => 1,
                'name' => 'Foo',
            ],
        ], $collection->sortBy('name')->all());

        $expected = [
            'baz' => [
                'id'   => 3,
                'name' => 'Baz',
            ],
            'bar' => [
                'id'   => 2,
                'name' => 'Bar',
            ],
            'foo' => [
                'id'   => 1,
                'name' => 'Foo',
            ],
        ];

        $output = $collection->sortByDesc('id')->all();

        $this->assertTrue($expected === $output);
    }

    /** @test */
    public function it_can_execute_the_before_callback_method()
    {
        $collection = new CollectionStub('foo');
        $collection->put('foo', new Collection('foo'));
        $collection->put('bar', new Collection('bar'));

        $this->assertCount(2, $collection->all());

        $this->assertTrue($collection->first()->valid);
        $this->assertEquals('Foo', $collection->first()->name);

        $this->assertTrue($collection->last()->valid);
        $this->assertEquals('Bar', $collection->last()->name);
    }

    /** @test */
    public function it_can_attach_a_collection()
    {
        $collection = new Collection('foo');

        $this->assertCount(0, $collection);

        $collection->attach(new Collection('foo'));
        $collection->attach(new Collection('bar'));
        $collection->attach(new Collection('baz'));
        $collection->attach(new Collection('foo'));

        $this->assertCount(3, $collection);
    }

    /** @test */
    public function it_can_make_an_item_on_a_collection_the_first_item()
    {
        $collection = new Collection('main');
        $collection->put('a', 'A');
        $collection->put('b', 'B');
        $collection->put('c', 'C');
        $collection->put('d', 'D');

        $this->assertEquals('A', $collection->first());

        $collection->makeFirst('c');

        $this->assertEquals('C', $collection->first());

        $collection->makeFirst('z');

        $this->assertEquals('C', $collection->first());
    }

    /** @test */
    public function it_can_make_an_item_on_a_collection_the_last_item()
    {
        $collection = new Collection('main');
        $collection->put('a', 'A');
        $collection->put('b', 'B');
        $collection->put('c', 'C');
        $collection->put('d', 'D');

        $this->assertEquals('D', $collection->last());

        $collection->makeLast('c');

        $this->assertEquals('C', $collection->last());

        $collection->makeLast('z');

        $this->assertEquals('C', $collection->last());
    }
}

class CollectionStub extends Collection
{
    public function nameAttribute($name)
    {
        return "Test {$name}";
    }

    public function beforeCallback()
    {
        foreach ($this->items as $item) {
            $item->valid = true;
            $item->name = ucfirst($item->id);
        }
    }
}
