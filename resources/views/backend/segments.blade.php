@extends('layouts.backend')

@section('css')
    <style type="text/css">
        .input-group-addon {
            cursor: pointer;
        }
        .label {
            font-size: 100%;
            margin-right: 5px;
        }
        .label-close {
            cursor: pointer;
            margin-left: 2px;
        }
        .label-close:hover {
            color: red;
        }
        .tag {
            padding: 2px 0;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">西窗烛</h3>

                            <div class="box-tools">
                                <div class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-default" onclick="openAddModal()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="search" class="form-control pull-right"
                                               placeholder="请输入关键词">

                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
            </div>
            <div id="foo"></div>
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal Begin -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">添加摘抄</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal-name">摘抄</label>
                        <input type="text" class="form-control" id="add-segment" placeholder="摘抄" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="modal-name">作者</label>
                        <input type="text" class="form-control" id="add-author" placeholder="作者" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="modal-name">分类</label>
                        {!! getSelectorByConfig('backend.segments.categories', ['id' => 'add-cate', 'class' => 'form-control']) !!}
                        {{--<input type="text" class="form-control" id="add-cate" placeholder="分类">--}}
                    </div>
                    <div class="form-group">
                        <label for="add-tag">标签</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="add-tag" maxlength="5" placeholder="请输入标签并点击右侧添加按钮,最多3个标签">
                            <span class="input-group-addon" onclick="addTag()"><i class="fa fa-plus"></i></span>
                        </div>
                        <br>
                        <div class="tag">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-article">原文</label>
                        <textarea name="add-article" id="add-article" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="add-description">解析</label>
                        <textarea name="add-description" id="add-description" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="add-thoughts">感想</label>
                        <textarea name="add-thoughts" id="add-thoughts" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addSegment()">保存</button>
                    <button type="button" class="btn btn-default" onclick="closeAddModal()">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Modal End -->

@endsection

@section('js')
    <script>
        function openAddModal() {
            $("#add-modal").modal('show');
        }

        function addSegment() {
            var idArr = ['add-segment', 'add-author', 'add-article', 'add-description', 'add-thoughts', 'add-cate'];
            var data = getModalVal(idArr);
            data.tags = getClassGroupVal('.label.label-primary');

            $.ajax({
                'type': 'post',
                'url': 'segments',
                data: data,
                dataType: 'json',
                success: function (response) {
                    closeAddModal();
                },
                error: function (response) {
                    var msg = '';
                    var errors = response.responseJSON.errors;
                    $.each(errors, function (index, element) {
                        msg = msg + element + ' ';
                    });
                    alert(msg);
                }
            })
        }

        function addTag() {
            var addTag = $("#add-tag");
            var val = $.trim(addTag.val());
            addTag.val('');
            if (!$.trim(val)) {
                //不能为空
                return;
            }
            var tags = [];
            $('div.tag>span').each(function () {
                tags.push($.trim($(this).text()));
            });
            if(tags.length >= 5) return;
            if ($.inArray(val, tags) !== -1) {
                //不能重复
                return;
            }
            var html = '<span class="label label-primary">' + val + ' <i class="fa fa-close label-close" onclick="deleteTag(this)"></i></span>';
            $("div.tag").append(html);
        }
        
        function deleteTag(obj) {
            $(obj).parent().remove();
        }

        function closeAddModal() {
            $("div.tag").html('');
            $("#add-modal").modal('hide');
        }

        $('#add-modal').on('hidden.bs.modal', function () {
            clearForm($('#add-modal'));
        });
    </script>
@endsection

