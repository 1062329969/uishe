<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //
    const Comments_Status_Allow = 'allow';
    const Comments_Status_Refuse = 'refuse';

    protected $table = 'comments';
    protected $primaryKey = 'id';
}
