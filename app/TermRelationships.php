<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermRelationships extends Model
{
    //
    protected $table = 'wp_term_relationships';
    protected $primaryKey = 'object_id';
}
