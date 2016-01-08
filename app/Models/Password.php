<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Password extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Passwords';
}
