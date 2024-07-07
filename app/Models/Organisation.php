<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    protected $fillable = [
        'orgId', 'name', 'description',
    ];

    /**
     * Users that belong to the organisation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

