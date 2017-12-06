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
                                            <button type="submit" class="btn btn-default" onclick="search()"><i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>段落</th>
                                    <th>作者</th>
                                    <th>分类</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach ($segments as $key => $segment)
                                    <tr>
                                        <td>{{$segment->id}}</td>
                                        <td>{{$segment->segment}}</td>
                                        <td>{{$segment->author}}</td>
                                        <td>{{config('backend.segments.categories.' .$segment->cate)}}</td>
                                        <td>{{$segment->created_at}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="openUpdateModal('{{$segment->id}}')">修改</button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="deleteSegment('{{$segment->id}}')">删除</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{$segments->appends(['search' => $search])->links()}}
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
                    <h4 class="modal-title">添加段落</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-segment">段落</label>
                        <input type="text" class="form-control" id="add-segment" placeholder="段落" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="add-author">作者</label>
                        <input type="text" class="form-control" id="add-author" placeholder="作者" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="add-author">分类</label>
                        {!! getSelectorByConfig('backend.segments.categories', ['id' => 'add-cate', 'class' => 'form-control']) !!}
                        {{--<input type="text" class="form-control" id="add-cate" placeholder="分类">--}}
                    </div>
                    <div class="form-group">
                        <label for="add-tag">标签</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="add-tag" maxlength="5" placeholder="请输入标签并点击右侧添加按钮,最多3个标签">
                            <span class="input-group-addon" onclick="addTag('#add-tag', 'div.add-tag-div')"><i class="fa fa-plus"></i></span>
                        </div>
                        <br>
                        <div class="add-tag-div">
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

    <div class="modal fade" id="update-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">修改段落</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="update-id" name="update-id">
                    <div class="form-group">
                        <label for="update-segment">段落</label>
                        <input type="text" class="form-control" id="update-segment" name="update-segment" placeholder="段落" maxlength="200">
                    </div>
                    <div class="form-group">
                        <label for="update-author">作者</label>
                        <input type="text" class="form-control" id="update-author" name="update-author" placeholder="作者" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="update-cate">分类</label>
                        {!! getSelectorByConfig('backend.segments.categories', ['id' => 'update-cate', 'class' => 'form-control', 'name' => 'update-cate']) !!}
                        {{--<input type="text" class="form-control" id="add-cate" placeholder="分类">--}}
                    </div>
                    <div class="form-group">
                        <label for="update-tag">标签</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="update-tag" maxlength="5" placeholder="请输入标签并点击右侧添加按钮,最多3个标签">
                            <span class="input-group-addon" onclick="addTag('#update-tag', 'div.update-tag-div')"><i class="fa fa-plus"></i></span>
                        </div>
                        <br>
                        <div class="update-tag-div">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update-article">原文</label>
                        <textarea name="update-article" id="update-article" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="update-description">解析</label>
                        <textarea name="update-description" id="update-description" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="update-thoughts">感想</label>
                        <textarea name="update-thoughts" id="update-thoughts" cols="200" rows="10" class="form-control"
                                  maxlength="2000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateSegment()">保存</button>
                    <button type="button" class="btn btn-default" onclick="closeUpdateModal()">关闭</button>
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
                    window.location.reload();
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

        function addTag(inputSelector, divSelector) {
            var addTag = $(inputSelector);
            var val = $.trim(addTag.val());
            addTag.val('');
            if (!$.trim(val)) {
                //不能为空
                return;
            }
            addTagHtml(val, divSelector);
        }

        function addTagHtml(val, selector) {
            var tags = [];
            $(selector + '>span').each(function () {
                tags.push($.trim($(this).text()));
            });
            if(tags.length >= 5) return;
            if ($.inArray(val, tags) !== -1) {
                //不能重复
                return;
            }
            var html = '<span class="label label-primary">' + val + ' <i class="fa fa-close label-close" onclick="deleteTag(this)"></i></span>';
            $(selector).append(html);
        }

        function deleteTag(obj) {
            $(obj).parent().remove();
        }

        function closeAddModal() {
            $("div.add-tag-div").html('');
            $("#add-modal").modal('hide');
        }

        $('#add-modal').on('hidden.bs.modal', function () {
            clearForm($('#add-modal'));
        });

        function search() {
            var url = "{{url('/backend/segments/')}}";
            var val = $("[name='search']").val();
            url = url + '?search=' + val;
            window.location.href = url;
        }

        function openUpdateModal(id) {
            $.ajax({
                type: 'get',
                url: 'segment',
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    var updateModal = $("#update-modal");
                    updateModal.setModalVal(response, 'update-');
                    if (response.tags.length > 0) {
                        $.each(response.tags, function (index, ele) {
                            addTagHtml(ele.content, 'div.update-tag-div')
                        });
                    }
                    updateModal.modal('show');
                },
                error: function(response) {
                    var msg = '';
                    var errors = response.responseJSON.errors;
                    $.each(errors, function (index, element) {
                        msg = msg + element + ' ';
                    });
                    alert(msg);
                }
            });
        }

        function closeUpdateModal() {
            $("div.update-tag-div").html('');
            $("#update-modal").modal('hide');
        }

        $('#update-modal').on('hidden.bs.modal', function () {
            clearForm($('#add-modal'));
        });

        function updateSegment() {
            var idArr = ['update-id', 'update-segment', 'update-author', 'update-article', 'update-description', 'update-thoughts', 'update-cate'];
            var data = getModalVal(idArr);
            data.tags = getClassGroupVal('.label.label-primary');
            $.ajax({
                type: 'post',
                url: 'segment',
                data: data,
                dataType: 'json',
                success: function (response) {
                    window.location.reload();
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
        
        function deleteSegment(id) {
            var data = {id: id};
            $.ajax({
                type: 'delete',
                url: 'segment',
                data: data,
                dataType: 'json',
                success: function (response) {
                    window.location.reload();
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
    </script>
@endsection

