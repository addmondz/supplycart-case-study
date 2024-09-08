<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    protected $table = 'membership_types';

    protected $fillable = [
        'name',
        'discount',
    ];

    protected $casts = [
        'discount' => 'integer',
    ];

    /**
     * Get the users for the membership type.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
