<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as Model;
use Cviebrock\EloquentSluggable\Sluggable;

class RoleUser extends Model
{
    protected $table = 'role_users';

}
