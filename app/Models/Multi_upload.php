<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Multi_upload extends Model
{
    use SoftDeletes;
    //
    protected $table = 'multi_upload';

    //子分类
    public function upload_relation()
    {
        return $this->hasOne('App\Models\Upload', 'id', 'alpha_id');
    }

    public static function save_info($data, $rid, $model)
    {
        $has_data = Multi_upload::where(['rid' => $rid, 'model' => $model])->with("upload_relation")->get();
        $alpha_id_arr = array();
        foreach ($has_data as $index => $has_datum) {
            if (!empty($has_datum)) {
                $alpha_id_arr[] = $has_datum->upload_relation->id;
            }
        }
        $alpha_id_arr = array_unique($alpha_id_arr);

        if ($data) {
            $i = 1;
            foreach ($data as $alpha_id => $title) {
                $new_data = array(
                    'rid' => $rid,
                    'model' => $model,
                    'alpha_id' => $alpha_id,
                    'title' => $title,
                    'orders' => $i,
                );

                //已经有的修改
                if ($has_data && (($id = array_search($alpha_id, $alpha_id_arr)) !== false)) {
                    Multi_upload::where('alpha_id', $id)->update($new_data);
                    unset($alpha_id_arr[$id]);
                } else {
                    Multi_upload::insert($new_data);
                }
                $i++;
            }
        }

        //如果有多余的，删除
        if ($alpha_id_arr) {
            Multi_upload::whereIn('alpha_id', ($alpha_id_arr))->delete();
        }
    }
}
