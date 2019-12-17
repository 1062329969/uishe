function show_file(v) {
    var file = $("input[name=" + v + "_id]").val();
    if (file) {
        show_file_info(file);
    } else {
        alert('请先上传文件');
    }
    console.log(file);
}

function show_file_info(id) {
    //console.log(id);
    if (!id) {
        alert('参数错误');
        return false;
    }
    //console.log(id + 'AJAX');
    $.ajax({
        type: "get",
        url: "/file/show_file/" + id,
        dataType: 'json',
        success: function (data) {

            //console.log(data);
            if (data.status == 200) {
                var msg = data.msg;
                var sd = dialog({
                    title: '文件信息',
                    content: msg
                });
                sd.show();
            } else {
                alert('参数错误');
            }
        }
    });
    return false;
}
// (function ($, window) {
    var applicationPath = window.applicationPath === "" ? "" : window.applicationPath || "../..";

    function SuiJiNum() {
        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    }

    function initWebUpload(item, options) {
        if (!WebUploader.Uploader.support()) {
            var errors = "上传控件不支持您的浏览器！请尝试升级flash版本或者使用Chrome引擎的浏览器。<a target='_blank' href='http://se.360.cn'>下载页面</a>";
            if (window.console) {
                window.console.log(errors);
            }
            $(item).text(errors);
            return;
        }
        //创建默认参数
        var defaults = {
            auto: true,
            hiddenInputId: "uploadifyHiddenInputId", // input hidden id
            onAllComplete: function (event) {
            }, // 当所有file都上传后执行的回调函数
            onComplete: function (event) {
            },// 每上传一个file的回调函数
            innerOptions: {},
            fileNumLimit: undefined,//验证文件总数量, 超出则不允许加入队列
            fileSizeLimit: undefined,//验证文件总大小是否超出限制, 超出则不允许加入队列。
            fileSingleSizeLimit: undefined,//验证单个文件大小是否超出限制, 超出则不允许加入队列
            PostbackHold: false
        };
        var opts = $.extend(defaults, options);
        var hdFileData = $("#" + opts.hiddenInputId);
        console.log(item);
        var target = $(item);//容器
        var pickerid = "";
        if (typeof guidGenerator36 != 'undefined')//给一个唯一ID
            pickerid = guidGenerator36();
        else
            pickerid = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var uploaderStrdiv = '<div class="webuploader">'
        // debugger
        console.log(opts);

        if (opts.auto) {
            uploaderStrdiv =
                '<div id="Uploadthelist" class="uploader-list"></div>' +
                '<div class="btns">' +
                '<div id="' + pickerid + '">选择文件</div>' +
                '</div>' +
                '<div class="webuploadAllow">格式支持' + options.allowType + '格式。</div>'
        } else {
            console.log(target.find('.uploader-list'));
            if (target.find('.uploader-list'))
                uploaderStrdiv = '';
            else
                uploaderStrdiv = '<div  class="uploader-list"></div>';
            uploaderStrdiv += '<div class="btns">' +
                '<div id="' + pickerid + '">选择文件</div>' +
                '</div>' +
                '<div class="webuploadAllow">格式支持' + options.allowType + '格式。</div>'
        }
        uploaderStrdiv += '<div style="display:none" class="UploadhiddenInput" ></div>'
        // uploaderStrdiv += '</div>';
        target.append(uploaderStrdiv);

        var $list = target.find('.uploader-list'),
            $btn = target.find('.webuploadbtn'),//手动上传按钮备用
            state = 'pending',
            $hiddenInput = target.find('.UploadhiddenInput'),
            uploader;
        var jsonData = {
            fileList: []
        };
// alert(pickerid);
        var webuploaderoptions = $.extend({
                auto: true,
                // swf文件路径
                swf: '/js/Uploader.swf',
                // 文件接收服务端。
                server: 'upload/upload_file',
                deleteServer: '/Home/DeleteFile',
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#' + pickerid,
                //不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                fileNumLimit: opts.fileNumLimit,
                fileSizeLimit: opts.fileSizeLimit,
                fileSingleSizeLimit: opts.fileSingleSizeLimit,
                accept: options.accept
            },
            opts.innerOptions);
        var uploader = WebUploader.create(webuploaderoptions);

        //回发时还原hiddenfiled的保持数据
        var fileDataStr = hdFileData.val();
        if (fileDataStr && opts.PostbackHold) {
            jsonData = JSON.parse(fileDataStr);
            $.each(jsonData.fileList, function (index, fileData) {
                var newid = SuiJiNum();
                fileData.queueId = newid;
                if (options.limit) {
                    $list.html('<div id="' + newid + '" class="item">' +
                        '<div class="info">' + fileData.name + '</div>' +
                        '<div class="state">已上传</div>' +
                        '<div class="del"></div>' +
                        '</div>');
                } else {
                    $list.append('<div id="' + newid + '" class="item">' +
                        '<div class="info">' + fileData.name + '</div>' +
                        '<div class="state">已上传</div>' +
                        '<div class="del"></div>' +
                        '</div>');
                }
            });
            hdFileData.val(JSON.stringify(jsonData));
        }

        if (opts.auto) {

            uploader.on('fileQueued', function (file) {
                // debugger;
                if (options.limit) {
                    $list.html('<div id="' + $(item)[0].id + file.id + '" class="item">' +
                        '<span class="webuploadinfo" onclick="show_file(\'' + options.name + '\')">' + file.name + '</span>' +
                        '<div class="webuploadinfodiv"><span class="webuploadsize">' + WebUploader.formatSize(file.size) + '</span>' +
                        '<span class="webuploadstate">正在上传...</span>' +
                        '<div class="webuploadDelbtn">删除</div></div>' +
                        '</div>');
                }
                else {
                    $list.append('<div id="' + $(item)[0].id + file.id + '" class="item">' +
                        '<span class="webuploadinfo" onclick="show_file(\'' + options.name + '\')">' + file.name + '</span>' +
                        '<div class="webuploadinfodiv"><span class="webuploadsize">' + WebUploader.formatSize(file.size) + '</span>' +
                        '<span class="webuploadstate">正在上传...</span>' +
                        '<div class="webuploadDelbtn">删除</div></div>' +
                        '</div>');
                }
                uploader.upload();
            });
        } else {
            uploader.on('fileQueued', function (file) {//队列事件
                if (options.limit) {
                    $list.html('<div id="' + $(item)[0].id + file.id + '" class="item">' +
                        '<span class="webuploadinfo" onclick="show_file(\'' + options.name + '\')">' + file.name + '</span>' +
                        '<div class="webuploadinfodiv"><span class="webuploadsize">' + WebUploader.formatSize(file.size) + '</span>' +
                        '<span class="webuploadstate">等待上传...</span>' +
                        '<div class="webuploadDelbtn">删除</div></div>' +
                        '</div>');
                } else {
                    $list.append('<div id="' + $(item)[0].id + file.id + '" class="item">' +
                        '<span class="webuploadinfo" onclick="show_file(\'' + options.name + '\')">' + file.name + '</span>' +
                        '<div class="webuploadinfodiv"><span class="webuploadsize">' + WebUploader.formatSize(file.size) + '</span>' +
                        '<span class="webuploadstate">等待上传...</span>' +
                        '<div class="webuploadDelbtn">删除</div></div>' +
                        '</div>');
                }
            });
        }

        uploader.on('error', function (code, file) {
            var name = file.name;
            var str = "";
            switch (code) {
                case "F_DUPLICATE":
                    str = name + "文件重复";
                    alert(str);
                    break;
                case "Q_TYPE_DENIED":
                    str = name + "文件类型 不允许";
                    alert(str);
                    break;
                case "F_EXCEED_SIZE":
                    var imageMaxSize = 9;//通过计算
                    str = name + "文件大小超出限制" + imageMaxSize + "M";
                    alert(str);
                    break;
                case "Q_EXCEED_SIZE_LIMIT":
                    alert("超出空间文件大小");

                    break;
                case "Q_EXCEED_NUM_LIMIT":
                    alert("抱歉，超过每次上传数量图片限制");
                    break;
                default:
                    str = name + " Error:" + code;
                    alert(str);
                    break;
            }

        });

        uploader.on('uploadBeforeSend', function (block, data) {
            // block为分块数据。

            // file为分块对应的file对象。
            var file = block.file;

            // 修改data可以控制发送哪些携带数据。
            $.each(options.datas, function (k, v) {
                data[k] = v;
            });
        });

        uploader.on('uploadProgress', function (file, percentage) {//进度条事件
            var $li = target.find('#' + $(item)[0].id + file.id),
                $percent = $li.find('.progress .bar');

            // 避免重复创建
            if (!$percent.length) {
                $percent = $('<span class="progress">' +
                    '<span  class="percentage"><span class="text"></span>' +
                    '<span class="bar" role="progressbar" style="width: 0%">' +
                    '</span></span>' +
                    '</span>').appendTo($li).find('.bar');
            }

            $li.find('span.webuploadstate').html('上传中');
            $li.find(".text").text(Math.round(percentage * 100) + '%');
            $percent.css('width', percentage * 100 + '%');
        });
        uploader.on('uploadSuccess', function (file, response) {//上传成功事件
            // debugger
            console.log(response.message);
            if (typeof(response.code) == 'undefined' || typeof(response.message) == 'undefined' || response.code < 0) {
                if (typeof(response.message) == 'undefined') {
                    target.find('#' + $(item)[0].id + file.id).find('span.webuploadstate').html('上传失败');
                } else {
                    target.find('#' + $(item)[0].id + file.id).find('span.webuploadstate').html(response.message);
                }
            } else {
                target.find('#' + $(item)[0].id + file.id).find('span.webuploadstate').html('已上传');
                if (options.limit) {
                    // $hiddenInput.append('<input type="text" id="hiddenInput' + $(item)[0].id + file.id + '" class="hiddenInput" value="' + response.message + '" />');
                    $hiddenInput.append('<input type="hidden" name="' + options.name + '" value="' + response.file_info.full_path + '" />');
                    $hiddenInput.append('<input type="hidden" name="' + options.name + '_id" value="' + response.file_info.id + '" />');
                } else {
                    $hiddenInput.append('<input type="text" id="hiddenInput' + $(item)[0].id + file.id + '" class="hiddenInput" name="' + options.name + '[' + response.file_info.id + ']" value="' + response.file_info.client_name + '" />')
                }
            }

        });

        uploader.on('uploadError', function (file) {
            target.find('#' + $(item)[0].id + file.id).find('span.webuploadstate').html('上传出错');
        });

        uploader.on('uploadComplete', function (file) {//全部完成事件
            target.find('#' + $(item)[0].id + file.id).find('.progress').fadeOut();

        });

        uploader.on('all', function (type) {
            if (type === 'startUpload') {
                state = 'uploading';
            } else if (type === 'stopUpload') {
                state = 'paused';
            } else if (type === 'uploadFinished') {
                state = 'done';
            }

            if (state === 'uploading') {
                $btn.text('暂停上传');
            } else {
                $btn.text('开始上传');
            }
        });

        //删除时执行的方法
        uploader.on('fileDequeued', function (file) {
            // debugger

            var fullName = $("#hiddenInput" + $(item)[0].id + file.id).val();
            if (fullName != null) {
                $.post(webuploaderoptions.deleteServer, {fullName: fullName}, function (data) {
                    alert(data.message);
                })
            }
            $("#" + $(item)[0].id + file.id).remove();
            $("#hiddenInput" + $(item)[0].id + file.id).remove();

        });

        //多文件点击上传的方法
        $btn.on('click', function () {
            if (state === 'uploading') {
                uploader.stop();
            } else {
                uploader.upload();
            }
        });

        //删除
        $list.on("click", ".webuploadDelbtn", function () {
            // debugger
            var $ele = $(this);
            var id = $ele.parent().parent().attr("id");
            var id = id.replace($(item)[0].id, "");

            var file = uploader.getFile(id);
            uploader.removeFile(file);
        });

        $list.on("click", ".webuploadDbtn", function () {
            // debugger
            var $ele = $(this);
            $ele.parent().parent().remove();
        });

    }

    function GetFilesAddress(fn , options) {
        var ele = fn;
        var filesdata = ele.find(".UploadhiddenInput");
        var filesAddress = [];
        filesdata.find(".hiddenInput").each(function () {
            filesAddress.push($(this).val());
        });
        return filesAddress;

    };

   function powerWebUpload(fn ,options) {
        var ele = fn;
        if (typeof WebUploader == 'undefined') {
            var casspath = "/css/webuploader.css";
            $("<link>").attr({rel: "stylesheet", type: "text/css", href: casspath}).appendTo("head");
            var jspath = "/js/webuploader.min.js";
            $.getScript(jspath).done(function () {
                initWebUpload(ele, options);
            })
                .fail(function () {
                    alert("请检查webuploader的路径是否正确!")
                });

        }
        else {
            initWebUpload(ele, options);
        }
    }

// })(jQuery, window);