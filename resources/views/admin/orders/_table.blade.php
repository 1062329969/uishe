<table class="layui-table">
    <thead>
    <tr>
        <th colspan="2" align="center"> 订单详情 </th>
    </tr>
    </thead>
    <colgroup>
        <col width="100">
        <col width="200">
        <col>
    </colgroup>

    <tbody>

    <tr>
        <td>订单编号</td>
        <td>  {{$orders->order_no}}</td>
    </tr>
    <tr>
        <td>支付类型</td>
        <td>  {{$orders->order_type}}</td>
    </tr>
    <tr>
        <td>订单金额</td>
        <td>  {{$orders->actual_price}}</td>
    </tr>
    <tr>
        <td>支付方式</td>
        <td>  {{$orders->pay_type}}</td>
    </tr>
    <tr>
        <td>支付时间</td>
        <td>  {{$orders->pay_at}}</td>
    </tr>
    <tr>
        <td>用户ID</td>
        <td>  {{$orders->pay_user_id}}</td>
    </tr>

    </tbody>
</table>

@include('admin.orders.'.$orders->order_type.'_table')

