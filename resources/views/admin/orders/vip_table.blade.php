@foreach($orders->order_vip as $vip_info)
    <table class="layui-table">
        <colgroup>
            <col width="100">
            <col width="200">
            <col>
        </colgroup>

        <tbody>
        <tr>
            <td colspan="2">{{$orders->order_type}}记录</td>
        </tr>
        <tr>
            <td>会员等级</td>
            <td>  {{$vip_info->vip_level}}</td>
        </tr>
        <tr>
            <td>会员名称</td>
            <td>{{$vip_info->vip_name}}</td>
        </tr>
        </tbody>
    </table>
@endforeach
