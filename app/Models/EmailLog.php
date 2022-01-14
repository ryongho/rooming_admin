<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'email',
        'title',
        'content',
        'send_date',
        'state',
        'created_at',
    ];
}
