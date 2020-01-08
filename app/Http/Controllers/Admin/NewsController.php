<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewsRequest;
use App\Models\CategoryNew;
use App\Models\News;
use App\Models\Category;
use App\Models\Tag;
use App\Models\TagNew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //分类
        $categorys = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
        return view('admin.news.index',compact('categorys'));
    }

    public function data(Request $request)
    {

        $model = News::query();
        if ($request->get('category_id')){
            $model = $model->where('category_id',$request->get('category_id'));
        }
        if ($request->get('title')){
            $model = $model->where('title','like','%'.$request->get('title').'%');
        }
        $res = $model->orderBy('created_at','desc')->with(['tags','category'])->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //分类
        $categorys = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
        //标签
        $tags = Tag::select(['id', 'name'])->get();
        return view('admin.news.create',compact('tags','categorys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
//        dd($request->toArray());
        DB::beginTransaction();
        $tag_id = $request->tags;
        $tag_new = [];
        $request->new_tags = $request->new_tags ?? [];
        foreach($request->new_tags as $item){
            $new_tag_id = Tag::save_tag(['name'=>$item]);
            if( !$new_tag_id ){
                DB::rollBack();
                return redirect(route('admin.news'))->withErrors(['status'=>'添加失败']);
            }
            $tag_new[] = [
                'tag_id' => $new_tag_id,
                'tag' => $item,
            ];
            $tag_id[] = $new_tag_id;
        }

        $data = $request->only([
            'category_id',
            'title',
            'content',
            'cover_img',
            'down_type',
            'views',
            'down_level',
            'down_price',
            'comment_status',
            'recommend',
            'down_url',
            'introduction'
        ]);
        $data['admin_id'] = Auth::id();
        $data['status'] = News::Status_Normal;
        $data['comment_status'] = $request->comment_status;
        $data['recommend'] = $request->recommend;
        $data['tag_id'] = json_encode($tag_id);
        $data['tag'] = json_encode($request->tags_name, JSON_UNESCAPED_UNICODE);
        $data['category'] = Category::where('id', $request->category_id)->value('name');
        $news = News::create($data);
        if ($news){
            if(!empty($request->get('new_tags'))){
                foreach ($tag_new as &$item){
                    $item['tag_new_id'] = $news->id;
                }
                $tag_new_bool = TagNew::insert($tag_new);
                if(!$tag_new_bool){
                    DB::rollBack();
                    return redirect(route('admin.news'))->withErrors(['status'=>'添加失败']);
                }
            }
            $category_new_bool = CategoryNew::insert([
                'cat_new_id' => $news->id,
                'cat_id' => $request->category_id,
                'category' => $data['category'],
            ]);
            if(!$category_new_bool){
                DB::rollBack();
                return redirect(route('admin.news'))->withErrors(['status'=>'添加失败']);
            }

            DB::commit();
            return redirect(route('admin.news'))->with(['status'=>'添加成功']);
        }else{
            return redirect(route('admin.news'))->withErrors(['status'=>'添加失败']);
        }
    }

    /**
     * @param  int  $id
     *
     */
    public function getbycategory($id)
    {
        $news_list = News::where([
                ['status', '=', News::Status_Normal],
                ['category_id', '=', $id],
            ])
            ->select(['id', 'title', 'cover_img'])
            ->get()
            ->toArray();
        if($news_list){
            $news_list = array_column($news_list, NULL, 'id');
        }
        return response()->success($news_list);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::with('tags')->findOrFail($id);
        if (!$news){
            return redirect(route('admin.news'))->withErrors(['status'=>'文章不存在']);
        }
        //分类
        $categorys = Category::with('allChilds')->where('parent_id',0)->orderBy('sort','desc')->get();
        //标签
        $tags = Tag::select(['id', 'name'])->get();
        return view('admin.news.edit',compact('news','categorys','tags'));

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
        DB::beginTransaction();
        $tag_id = $request->tags;
        $tag_new = [];
        $request->new_tags = $request->new_tags ?? [];
        foreach($request->new_tags as $item){
            $new_tag_id = Tag::save_tag(['name'=>$item]);
            if( !$new_tag_id ){
                DB::rollBack();
                return redirect(route('admin.news'))->withErrors(['status'=>'添加失败']);
            }
            $tag_new[] = [
                'tag_id' => $new_tag_id,
                'tag' => $item,
            ];
            $tag_id[] = $new_tag_id;
        }

        $news = News::with('tags')->findOrFail($id);

        $data = $request->only([
            'category_id',
            'title',
            'content',
            'cover_img',
            'down_type',
            'views',
            'down_level',
            'down_price',
            'comment_status',
            'recommend',
            'down_url',
            'introduction'
        ]);
        $data['comment_status'] = $request->comment_status ?? News::Comment_Status_Off;
        $data['recommend'] = $request->recommend ?? News::Recommend_Off;
        $data['tag_id'] = json_encode($tag_id);
        $data['tag'] = json_encode($request->tags_name, JSON_UNESCAPED_UNICODE);
        $data['category'] = Category::where('id', $request->category_id)->value('name');

        if ($news->update($data)){

            if(!empty($request->get('new_tags'))){
                foreach ($tag_new as &$item){
                    $item['tag_new_id'] = $news->id;
                }
                $tag_new_bool = TagNew::insert($tag_new);
                if(!$tag_new_bool){
                    DB::rollBack();
                    return redirect(route('admin.news'))->withErrors(['status'=>'更新失败']);
                }
            }
            $category_new_bool = CategoryNew::where(['cat_new_id' => $news->id])->update([
                'cat_id' => $request->category_id,
                'category' => $data['category'],
            ]);

            DB::commit();
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
