<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewsRequest;
use App\Models\Category;
use App\Models\WebOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $op_type = $weboption;
        switch ($op_type){
            case 'category':
                $categorys = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
                $data['categorys'] = $categorys;
                $data['op_type'] = $op_type;
                break;
            case 'links':

                break;
            case 'banner':

                break;
            case 'index':

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
    public function update(NewsRequest $request, $id)
    {
        $news = News::with('tags')->findOrFail($id);
        $data = $request->only(['category_id','title','keywords','description','content','thumb','click']);
        if ($news->update($data)){
            $news->tags()->sync($request->get('tags',[]));
            return redirect(route('admin.news'))->with(['status'=>'更新成功']);
        }
        return redirect(route('admin.news'))->withErrors(['status'=>'系统错误']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        foreach (News::whereIn('id',$ids)->get() as $model){
            //清除中间表数据
            $model->tags()->sync([]);
            //删除文章
            $model->delete();
        }
        return response()->json(['code'=>0,'msg'=>'删除成功']);
    }

}
