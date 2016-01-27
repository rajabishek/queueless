<?php

namespace Queueless;

use Gravatar;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fullname', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * A user belongs to an organisation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo('Queueless\Organisation');
    }

    /**
     * An employee can have appointment with different users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Queueless\User')->withTimestamps();
    }

    /**
     * Check whether the user has the given role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return $role == $this->designation;
    }

    /**
     * Check the route of the home page
     *
     * @return string
     */
    public function getHomeRoute()
    {
        if($this->hasRole('Admin'))
            return 'admin.employees.index';
    }

    /**
     * Get the user's avatar image.
     *
     * @return string
     */
    public function getPhotocssAttribute()
    {
        if($this->profile && app('filesystem')->disk('local')->exists('public/uploads/profiles' . '/' . $this->profile)) {
            return url('uploads/profiles/' . $this->profile);
        }

        return Gravatar::src($this->email, 100);
    }
}