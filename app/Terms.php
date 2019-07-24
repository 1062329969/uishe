<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    //
    protected $table = 'wp_terms';
    protected $primaryKey = 'term_id';
}
