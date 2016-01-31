<?php

namespace Queueless;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * A user can have appointments with different employees.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany('Queueless\Employee')
                    ->withPivot('attending')
                    ->withTimestamps();
    }

    /**
     * A user can request appointment with many organisations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organisations()
    {
        return $this->belongsToMany('Queueless\Organisation')->withTimestamps();
    }
}
