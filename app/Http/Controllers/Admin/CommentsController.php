<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.comments.index');
    }

    public function data(Request $request)
    {

        $model = Comments::query();
        $res = $model->where(['parent_id' => 0])->orderBy('id', 'desc')->paginate($request->get('limit', 30))->toArray();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res['total'],
            'data' => $res['data']
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //
        $reply_id = $request->reply_id;
        $comment_id = $request->comment_id;
        $comment_info = Comments::find($comment_id);

        if ($reply_id) { // 修改回复
            $reply_comment_info = Comments::find($reply_id);
            $reply_comment_info->content = $request->content;
            $reply_comment_info->save();
            return redirect(route('admin.comments'))->with(['status' => '回复成功']);
        } else { // 新增回复
            $reply_comment = new Comments();
            $reply_comment->content = $request->content;
            $reply_comment->user_id = 1;
            $reply_comment->user_name = 'UI社';
            $reply_comment->status = 'allow';
            $reply_comment->parent_id = $comment_id;
            $reply_comment->new_id = $comment_info['new_id'];
            $rs = $reply_comment->save();
            return redirect(route('admin.comments'))->with(['status' => '回复成功']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comments = Comments::findOrFail($id);
        if (!$comments) {
            return redirect(route('admin.comments'))->withErrors(['status' => '评论不存在']);
        }

        $reply_comments = Comments::where(['parent_id' => $id])->first();

        return view('admin.comments.edit', compact('comments', 'reply_comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['code' => 1, 'msg' => '请选择删除项']);
        }
        foreach (Comments::whereIn('id', $ids)->get() as $model) {
            //清除中间表数据
//            $model->tags()->sync([]);
            //删除文章
            $model->delete();
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }
}
