<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller as BaseController;

// Test that all models extend the Eloquent Model class
expect('App\Models\*')
    ->toExtend(Model::class);

// Test that all controllers extend the base Controller class
expect('App\Http\Controllers\*Controller*')
    ->toExtend(BaseController::class);

// Test that models do not depend on controllers
expect('App\Models\*')
    ->not->toUse('App\Http\Controllers\*');

// Test that facades are only used in appropriate layers
expect('App\Models\*')
    ->not->toUse('Illuminate\Support\Facades\*');

// Test that controllers do not use direct DB facade calls
expect('App\Http\Controllers\*')
    ->not->toUse('Illuminate\Support\Facades\DB');