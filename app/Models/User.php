<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelModel;
use function file_exists;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\File;

use Image;
use function public_path;

class User extends SentinelModel implements Authenticatable
{
    use AuthenticableTrait;

    /**
     * Default password.
     *
     * @var string
     */
    public const DEFAULT_PASSWORD = '12345678';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'email',
        'password',
        'permissions',
        'name',
        'image',
        'address',
        'phone',
        'gender',
        'status',
        'last_login',
    ];

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
    ];

    public static function byEmail($email)
    {
        return static::whereEmail($email)->first();
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function datatables()
    {
        return static::select('name', 'address', 'status', 'id', 'email', 'created_at', 'phone')->where('id', '!=', '1');
    }

    /**
     * Get user's profile picture.
     *
     * @return string
     */
    public function isSuperAdmin()
    {
        if ($this->roles[0]->slug == 'super-admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Uploads an image.
     *
     * @param      <type>  $image  The image
     * @return     <type>  ( description_of_the_return_value )
     */
    public function uploadImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        $path      = public_path('uploads/user/');

        if (! file_exists($path)) {
            File::makeDirectory($path, 0777, true);
        }

        $name = $this->id . '.' . $extension;
        $img  = Image::make($image->getRealPath());
        $img->save($path . $name);

        return $this->update(['image' => $name]);
    }

    /**
     * The roles that belong to the user.
     */
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function scopeSuspend($query, $email)
    {
        return $query->where('email', $email)->where('status', 0)->get();
    }
}
