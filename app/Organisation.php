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
	 * @return mixed
	 */
	public function employees()
    {
        return $this->hasMany('Queueless\Employee');
    }
}
