@extends('layouts.backend')

@section('css')

@endsection

@section('content')
    <div class="content-wrapper" id="myModule">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">管理员</h3>

                            <div class="box-tools">
                                <div class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-default" onclick="showAddUser()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <input type="text" name="search" class="form-control pull-right" placeholder="请输入姓名或邮箱" value="{{$search}}">

                                        <div class="input-group-btn">
                                            <button type="submit" id="search-btn" class="btn btn-default"><i class="fa fa-search"></i></button>
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
                                    <th>姓名</th>
                                    <th>邮箱</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->created_at}}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="showUserModal('{{$user->id}}')">修改</button>
                                            <button type="button" class="btn btn-sm btn-info" onclick="deleteUser('{{$user->id}}')">删除</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            {{$users->appends(['search' => $search])->links()}}
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal Begin -->
    <div class="modal fade" id="user-model" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">管理员信息</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="modal-id">
                    <div class="form-group">
                        <label for="modal-name">姓名</label>
                        <input type="text" class="form-control" id="modal-name" placeholder="姓名">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateUser()">保存</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="add-user-model" tabindex="-1" role="dialog">
        <div class="modal-dialog  modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">添加管理员</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal-name">姓名</label>
                        <input type="text" class="form-control" id="add-modal-name" placeholder="姓名">
                    </div>
                    <div class="form-group">
                        <label for="add-modal-email">邮箱</label>
                        <input type="text" class="form-control" id="add-modal-email" placeholder="邮箱">
                    </div>
                    <div class="form-group">
                        <label for="add-modal-password">密码</label>
                        <input type="text" class="form-control" id="add-modal-password" placeholder="密码">
                    </div>
                    <div class="form-group">
                        <label for="add-modal-password-confirmation">确认密码</label>
                        <input type="text" class="form-control" id="add-modal-password-confirmation" placeholder="确认密码">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addUser()">保存</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Modal End-->
@endsection

@section('js')
    <script>
        $("#search-btn").click(function () {
            var url = "{{url('/backend/users/')}}";
            var val = $("[name='search']").val();
            url = url + '?search=' + val;
            window.location.href = url;
        });

        function showUserModal(id) {
            $.ajax({
                type: 'get',
                url: 'user',
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    $("#modal-name").val(response.name);
                    $("#modal-id").val(response.id);
                    $("#user-model").modal('show');
                }
            });
        }
        
        function updateUser() {
            var data = {
                id: $('#modal-id').val(),
                name: $('#modal-name').val()
            };

            $.ajax({
                type: 'post',
                url: 'user',
                data: data,
                dataType: 'json',
                success: function (response) {
                    alert('保存成功');
                    $("#user-model").modal('hide');
                    window.location.reload();
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

        function deleteUser(id) {
            $.ajax({
                type: 'delete',
                url: 'user',
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    window.location.reload();
                },
                error: function (response) {
                    console.log(response);
                    alert('删除失败');
                }
            })
        }

        function showAddUser() {
            $('#add-user-model').modal('show');
        }

        function addUser() {
            var data = {
                name: $('#add-modal-name').val(),
                email: $('#add-modal-email').val(),
                password: $('#add-modal-password').val(),
                password_confirmation: $('#add-modal-password-confirmation').val()
            };
            $.ajax({
                type: 'post',
                url: 'user',
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
            });
        }

        console.log('{{url()->current()}}');
    </script>
@endsection

