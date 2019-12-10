@extends('layouts.user')

@section('content')
    <div id="selfinfo">
        <div id="selfinfo_tag">
            <a class="edit_info @if($tab == 'edit_info') tag_on @endif" >编辑资料</a>
            <a class="bind_account @if($tab == 'bind_account') tag_on @endif" >绑定账号</a>
            <a class="edit_password @if($tab == 'edit_password') tag_on @endif" >修改密码</a>
        </div>

        @if($tab == 'edit_info')
            <div id="edit_info">
                <form id="edit_info" method="post" action="{{ route('saveself', ['tab' => $tab]) }}">
                    <div class="form-group height_72">
                        <label for="display_name" class="edit_info_label">昵称：</label>
                        <div class="edit_info_form_item">
                            <input type="text" class="form-control" name="display_name" value="">
                        </div>
                    </div>
                    <div class="form-group height_72">
                        <label for="display_name" class="edit_info_label">邮箱：</label>
                        <div class="edit_info_form_item">
                            <input type="text" class="form-control" name="email" value="">
                        </div>
                    </div>
                    <div class="form-group height_72">
                        <label for="display_name" class="edit_info_label">&nbsp;</label>
                        <div class="edit_info_form_item">
                            <input type="submit" value="保存" class="submit_button">
                        </div>
                    </div>
                </form>
                <div id="edit_info_img">

                </div>
            </div>
        @endif
        @if($tab == 'bind_account')
            <div id="bind_account" >
                <form>
                    aaaaaaaaaaaaaaaa
                </form>
            </div>
        @endif
        @if($tab == 'edit_password')
            <div id="edit_password" >
                <form>
                    aaaaaaaaaaaaaaaa
                </form>
            </div>
        @endif
    </div>
    <style>
        #selfinfo{ background: #fafafa }

        #selfinfo_tag{ width: 100%;background: #f1f1f1; }
        #selfinfo_tag a{ display: inline-block; width: 100px; height: 50px;line-height: 50px;text-align: center;border-right:1px #ccc solid;  }

        .form-group{height:100px;line-height:32px;border-bottom:1px solid #f3f3f3;line-height: 100px;}
        .edit_info_label{ float: left;width: 25%;text-align: right;padding-right: 15px; }
        .edit_info_form_item{width:50%;float: left}
        .form-control {width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);box-shadow:inset 0 1px 1px rgba(0, 0, 0, .075);-webkit-transition:border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s, box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s, box-shadow ease-in-out .15s}

        .submit_button{
            width: 200px;
            height: 50px;
            border: 0;
            background: #f66;
            color: #fff;
            font-size: 18px;
            border-radius: 5px;
        }

        #edit_info{ width: 70% }
        #edit_info_img{ width: 30% }

        .tag_on{background: #fff;border: 2px #f1f1f1 solid;border-bottom: 0; }
    </style>
    <script>
        $(function () {


        })
    </script>
@endsection

