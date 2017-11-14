<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
	use SoftDeletes;

	/**
	 * attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Do things a bit memory safe in pagination
     */
    const MAX_PER_PAGE = 50;

    /**
     * Get the comments for the blog post.
     *
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    } 

}
