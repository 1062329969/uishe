<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpComments extends Model
{
    //
    protected $table = 'wp_comments';
    protected $primaryKey = 'comment_ID';
}
