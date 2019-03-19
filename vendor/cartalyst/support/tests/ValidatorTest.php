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

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Cartalyst\Support\Validator;
use Illuminate\Validation\Factory as IlluminateValidator;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Validator instance.
     *
     * @var \Cartalyst\Support\Validator
     */
    protected $validator;

    /**
     * Setup resources and dependencies
     */
    public function setUp()
    {
        $this->validator = new ValidatorStub(
            $this->getRealValidator()
        );
    }

    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $validator = new ValidatorStub(
            $this->getRealValidator()
        );

        $this->assertInstanceOf('Cartalyst\Support\Validator', $validator);
    }

    /** @test */
    public function it_can_get_and_set_the_rules()
    {
        $this->assertCount(1, $this->validator->getRules());

        $this->validator->setRules([]);
        $this->assertCount(0, $this->validator->getRules());

        $this->validator->setRules([
            'name'  => 'required',
            'email' => 'required',
        ]);
        $this->assertCount(2, $this->validator->getRules());
    }

    /** @test */
    public function it_can_get_and_set_the_messages()
    {
        $this->validator->setMessages([
            'name'  => 'name is required',
            'email' => 'email is required'
        ]);
        $this->assertCount(2, $this->validator->getMessages());

        $this->validator->setMessages([]);
        $this->assertCount(0, $this->validator->getMessages());
    }

    /** @test */
    public function it_can_get_and_set_the_custom_attributes()
    {
        $this->validator->setCustomAttributes([
            'first_name' => 'First Name',
            'last_name'  => 'Last Name'
        ]);
        $this->assertCount(2, $this->validator->getCustomAttributes());

        $this->validator->setCustomAttributes([]);
        $this->assertCount(0, $this->validator->getCustomAttributes());
    }

    /** @test */
    public function it_can_define_scenarios()
    {
        $scenario = $this->validator->on('update', [ 'foo' ]);

        $this->assertInstanceOf('Cartalyst\Support\Validator', $scenario);
    }

    /** @test */
    public function it_can_register_bindings()
    {
        $this->validator->bind([ 'foo' => 'bar' ]);
    }

    /** @test */
    public function it_can_validate()
    {
        $messages = $this->validator->validate([]);

        $this->assertCount(1, $messages);

        $messages = $this->validator->on('update')->bind([ 'email' => 'popop@asdad.com' ])->validate([
            'email' => 'john@doe.com'
        ]);

        $this->assertTrue($messages->isEmpty());
    }

    /** @test */
    public function it_can_by_pass_tests()
    {
        $this->validator->bypass();

        $messages = $this->validator->validate([]);

        $this->assertTrue($messages->isEmpty());
    }

    protected function getRealValidator()
    {
        $trans = new \Illuminate\Translation\Translator(new \Illuminate\Translation\ArrayLoader(), 'en');

        return new IlluminateValidator($trans);
    }
}

class ValidatorStub extends Validator
{
    protected $rules = [
        'email' => 'required|email',
    ];

    public function onUpdate()
    {
    }
}
