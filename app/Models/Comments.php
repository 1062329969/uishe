<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comments extends Model
{
    use SoftDeletes;
    //
    const Comments_Status_Allow = 'allow';
    const Comments_Status_Refuse = 'refuse';

    protected $table = 'comments';
    protected $primaryKey = 'id';

    public static function getRecommendComments(){
        $recommend = Comments::where([
            ['status', '=', 'allow'],
            ['recommend', '=', 'on'],
        ])
            ->select(['content', 'new_id', 'user_id', 'user_name', 'created_at'])
            ->get()
            ->toArray();

        foreach ($recommend as &$item){
            $item['new_title'] = News::where('id', $item['new_id'])->value('title');
        }

        return $recommend;
    }
}
