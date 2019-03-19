<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as Model;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelModel;

use File;
use Image;

class User extends SentinelModel implements Authenticatable
{
 	use AuthenticableTrait;

    /**
     * Default password.
     *
     * @var string
     */
    const DEFAULT_PASSWORD = '12345678';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
    'email',
    'password',
    'permissions',
    'first_name',
    'last_name',
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
    'password'
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
        return static::select( 'first_name', 'address', 'status', 'id', 'email', 'created_at', 'phone' )->where('id', '!=', '1');
    }

    /**
     * Get user's profile picture.
     *
     * @return string
     */
    public function isSuperAdmin() {
        if ( $this->roles[0]->slug == 'super-admin' ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Uploads an image.
     *
     * @param      <type>  $image  The image
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function uploadImage( $image )
    {

        $extension = $image->getClientOriginalExtension();
        $path = public_path('uploads/user/');

        if (!file_exists( $path )) {
            File::makeDirectory($path,0777,true);
        }

        $name = $this->id.'.'.$extension;
        $img  = Image::make($image->getRealPath());
        $img->save($path.$name);

        $banner = $this->update([ 'image' => $name]);    
        
        return $banner;    
    }


    /**
    * The roles that belong to the user.
    */
   public function role()
   {
       return $this->belongsToMany('App\Models\Role', 'role_users');
   }

   public function scopeSuspend( $query, $email ){
        $data = $query->where('email', $email)->where('status', 0)->get();
        return $data;
    }
}
