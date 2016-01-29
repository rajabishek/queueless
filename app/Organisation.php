<?php

namespace Queueless;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['name','subdomain'];

	/**
	 * An organisations has many employees.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function employees()
    {
        return $this->hasMany('Queueless\Employee');
    }

    /**
     * Many users can request for an apoointment with an organisation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('Queueless\User')->withTimestamps();
    }
}
