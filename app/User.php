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
     * A user belongs to an organisation.
     *
     * @return mixed
     */
    public function organisation()
    {
        return $this->belongsTo('Queueless\Organisation');
    }
}
