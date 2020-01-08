<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\PasswordHash;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.member.index');
    }

    public function data(Request $request)
    {

        $model = User::query();

        $res = $model->orderBy('created_at', 'desc')->paginate($request->get('limit', 30))->toArray();
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
    public function store(Request $request, $id)
    {

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
        $member = User::findOrFail($id);
        if (!$member) {
            return redirect(route('admin.member'))->withErrors(['status' => '订单不存在']);
        }

        return view('admin.member.edit', compact('member'));
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
        $data = $request->only([
            'password',
            'password_confirmation',
        ]);

        if (!empty($data['password']) && $data['password'] != $data['password_confirmation']) {
            return back()->withErrors(['status' => '密码输入不正确']);
        }

        $user_info = User::find($id);

        $wp_hasher = new PasswordHash(8, TRUE);
        $update_data = $request->only([
            'email',
            'avatar_url',
        ]);;

        $update_data['password'] = $wp_hasher->HashPassword($data['password']);

        if ($user_info->update($update_data)) {
            return redirect(route('admin.member'))->with(['status' => '更新成功']);
        }
        return redirect(route('admin.member'))->withErrors(['status' => '系统错误']);
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
        foreach (User::whereIn('id', $ids)->get() as $model) {
            //清除中间表数据
//            $model->tags()->sync([]);
            //删除文章
            $model->delete();
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }
}
