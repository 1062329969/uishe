<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Orders_pay;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay as Ypay;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_type = Orders::order_type;
        return view('admin.orders.index', compact('order_type'));
    }

    public function data(Request $request)
    {

        $model = Orders::query();
        if ($request->get('order_type')) {
            $model = $model->where('order_type', $request->get('order_type'));
        }
        if ($request->get('order_no')) {
            $model = $model->where('order_no', 'like', '%' . $request->get('order_no') . '%');
        }
        $res = $model->whereNotNull('pay_at')->orderBy('created_at', 'desc')->paginate($request->get('limit', 30))->toArray();
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
        $orders = Orders::findOrFail($id);
        if (!$orders) {
            return redirect(route('admin.orders'))->withErrors(['status' => '订单不存在']);
        }

        return view('admin.orders.edit', compact('orders'));
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
        foreach (Orders::whereIn('id', $ids)->get() as $model) {
            //清除中间表数据
//            $model->tags()->sync([]);
            //删除文章
            $model->delete();
        }
        return response()->json(['code' => 0, 'msg' => '删除成功']);
    }

    // 退款
    public function refund($order_id)
    {
        $order_pay = Orders_pay::where(['order_id' => $order_id])->first();
        $order_no = random_string(25);
        $config_biz = [
            'out_trade_no' => $order_pay['orders_pay_code'],
            'refund_amount' => $order_pay['actual_price'],
            'out_request_no' => $order_no,
            'subject' => '退款',
        ];
        $rs = YPay::alipay()->refund($config_biz);
        if ($rs['code'] == "10000") {
            $order_pay->refund_code = $order_no;
            $order_pay->refund_no = $rs['out_trade_no'];
            $order_pay->refund_price = $order_pay['actual_price'];
            $order_pay->refund_at = date('Y-m-d H:i:s');
            $order_pay->save();
        }
    }
}
