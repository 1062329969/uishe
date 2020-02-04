<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\WebOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WebOptionController extends Controller
{

    private $op_type = [
            [
                'name' => '导航栏显示分类',
                'value' => 'category',
            ],
            [
                'name' => '友情链接',
                'value' => 'links',
            ],
            [
                'name' => '轮播图',
                'value' => 'banner',
            ],
            [
                'name' => '首页配置',
                'value' => 'index',
            ],
        ];

    public function index()
    {

        return view('admin.weboption.index');
    }

    public function data(Request $request)
    {

        $op_type = $this->op_type;
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'data'  => $op_type
        ];
        return response()->json($data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $weboption)
    {
        switch ($weboption){
            case 'category':
                $all_option = WebOption::where('op_type', $weboption)->orderBy('op_sort','desc')->get();
                $categorys = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
                $all_categorys = Category::get();
                $data['categorys'] = $categorys;
                $data['all_categorys'] = array_column($all_categorys->toArray(), NULL, 'id');
                $data['op_type'] = $weboption;
                $data['all_option'] = $all_option;
                break;
            case 'links':
                $all_option = WebOption::where('op_type', $weboption)->orderBy('op_sort','desc')->get();
                $data['op_type'] = $weboption;
                $data['all_option'] = $all_option;
                break;
            case 'banner':
                $all_option = WebOption::where('op_type', $weboption)->orderBy('op_sort','desc')->get();
                $data['op_type'] = $weboption;
                $data['all_option'] = $all_option;
                break;
            case 'index':
                $all_option = WebOption::where('op_type', $weboption)->value('op_value');
//                dd($all_option);
                $data['op_type'] = $weboption;
                $data['all_option'] = json_decode($all_option, true);
                //分类
                $data['categorys'] = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
                $data['all_categorys'] = Category::select(['id', 'name'])->get();
                break;
        }
        return view('admin.weboption.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $weboption)
    {
        switch ($weboption){
            case 'category':
                DB::beginTransaction();
                $del_res = WebOption::where('op_type', $weboption)->delete();
                $add_res = WebOption::insert($request->data);
                if($add_res){
                    DB::commit();
                    return response()->success('修改成功');
                }
                DB::rollBack();
                return response()->error(500, '修改失败');
                break;

            case 'links':
                DB::beginTransaction();
                $data = [];
                foreach ($request->op_value as $index => $item){
                    if (!$item){
                        continue;
                    }
                    $data[] = [
                        'op_value' => $item,
                        'op_parameter' => $request->op_parameter[$index],
                        'op_sort' => $request->op_sort[$index],
                        'op_status' => 'enable',
                        'op_type' => $weboption,
                    ];
                }

                $del_res = WebOption::where('op_type', $weboption)->delete();
                $add_res = WebOption::insert($data);
                if($add_res){
                    DB::commit();
                    return redirect(route('admin.weboption'))->with(['status'=>'更新成功']);
                }
                DB::rollBack();
                return redirect(route('admin.weboption'))->withErrors(['status'=>'更新失败']);
                break;

            case 'banner':
                DB::beginTransaction();
                $data = [];
                foreach ($request->op_value as $index => $item){
                    if (!$item){
                        continue;
                    }
                    $data[] = [
                        'op_value' => $item,
                        'op_parameter' => $request->op_parameter[$index],
                        'op_sort' => $request->op_sort[$index],
                        'op_status' => 'enable',
                        'op_type' => $weboption,
                    ];
                }

                $del_res = WebOption::where('op_type', $weboption)->delete();
                $add_res = WebOption::insert($data);
                if($add_res){
                    DB::commit();
                    return redirect(route('admin.weboption'))->with(['status'=>'更新成功']);
                }
                DB::rollBack();
                return redirect(route('admin.weboption'))->withErrors(['status'=>'更新失败']);
                break;
            case 'index':
                $data = array_column($request->data, NULL, 'sort');
                ksort($data);
                foreach ($data as $sort => $item){
                    $category_id = $item['category_id'] ?? 0;
                    if (!$category_id) {
                        return response()->error(500, '主分类不能为空');
                    }
                    if ( count($item['content'])<=0 ) {
                        return response()->error(500, '内容不能为空');
                    }

                    $data[$sort]['category_alias'] = Category::where('id', $category_id)->value('name');
                }
                $index = [
                    'op_value' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'op_sort' => 1,
                    'op_status' => 'enable',
                    'op_type' => $weboption,
                ];
                $del_res = WebOption::where('op_type', $weboption)->delete();
                $add_res = WebOption::insert($index);
                if($add_res){
                    DB::commit();
                    return response()->success('修改成功');
                }
                DB::rollBack();
                return response()->error(500, '修改失败');
                break;

        }
    }

}
