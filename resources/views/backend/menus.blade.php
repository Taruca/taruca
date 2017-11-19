@extends('layouts.backend')

@section('css')
    <link href="/plugins/zTree/css/zTreeStyle/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .ztree {
            min-height: 300px;
        }

        .box.box-info {
            min-height: 400px;
        }

        .menu-tabs {
            padding: 5px;
        }

        .ztree li span.button.add {
            margin-left: 2px;
            margin-right: 2px;
            background-position: -144px 0;
            vertical-align: top;
            *vertical-align: middle
        }

    </style>
@endsection

@section('content')
    <div class="content-wrapper" id="menus-app">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <section class="col-md-4">
                    <div class="nav-tabs-custom menu-tabs">
                        <ul id="treeDemo" class="ztree"></ul>
                    </div>
                </section>
                <section class="col-md-8">
                    <div class="box box-info">
                        <div class="menu-selected" hidden="hidden">
                            <div class="box-header with-border">
                                <h3 class="box-title">修改菜单</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" action="{{url('backend/get_menu')}}" method="post">
                                <input type="text" id="id" name="id" title="" hidden>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">名称</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="名称" maxlength="20" v-model="formData.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="route" class="col-sm-2 control-label">路由</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="route" name="route"
                                                   placeholder="路由" v-model="formData.route">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="icon" class="col-sm-2 control-label">图标</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="icon" name="icon"
                                                   placeholder="图标" v-model="formData.icon">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="sort" class="col-sm-2 control-label">排序</label>

                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="sort" name="sort"
                                                   placeholder="排序" v-model="formData.sort">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="col-sm-2 control-label">描述</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="description" name="description"
                                                   placeholder="描述" v-model="formData.description">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="hide" id="hide" v-model="formData.hide"> 隐藏
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="button" class="btn btn-info pull-right" id="submit">确定</button>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                        <div class="menu-no-selected">
                            <div class="box-header with-border">
                                <h3 class="box-title">请选择菜单</h3>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js')
    <script src="/plugins/zTree/js/jquery.ztree.all.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            getMenus();
        });

        var data = {formData: {}};
        var vm = new Vue({
            el: '#menus-app',
            data: data
        });

                {{--http://www.treejs.cn/v3/main.php#_zTreeInfo--}}
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            callback: {
                onClick: zTreeOnClick,
                beforeRemove: zTreeBeforeRemove,
                beforeDrop: beforeDrop
            },
            edit: {
                enable: true,
                showRemoveBtn: true,
                removeTitle: "删除菜单",
                showRenameBtn: false,
                renameTitle: "重命名",
                drag: {
                    isMove: true,
                    isCopy: false
                }
            },
            view: {
                addHoverDom: addHoverDom,
                removeHoverDom: removeHoverDom
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）

        //点击菜单节点获取菜单数据
        function zTreeOnClick(event, treeId, treeNode) {
            if (treeNode.level === 0) {
                return;
            }
            $.ajax({
                type: "GET",
                url: "get_menu",
                data: {id: treeNode.id},
                dataType: "json",
                success: function (data) {
                    $(".menu-no-selected").hide();
                    $(".menu-selected").show();
                    vm.$set(vm, 'formData', data);
                }
            });
        }

        //用户自定义按钮(新增按钮)
        var newCount = 1;
        var maxMenuId = parseInt("{{DB::table('menus')->max('id')}}");
        function addHoverDom(treeId, treeNode) {
            if (treeNode.level > 1) {
                return false;
            }
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_"+treeNode.tId);
            if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                //如果没有初始化，则初始化maxMenuId
                var id = maxMenuId + newCount;
                var name = "新菜单" + (newCount);
                //后台增加菜单
                var data = {
                    id: id,
                    parent_id: treeNode.id,
                    icon: 'fa-list',
                    name: name,
                    route: '#',
                    description: name,
                    sort: 999,
                    hide: 0,
                    level: treeNode.level
                };
                $.ajax({
                    type: 'post',
                    url: 'store_menu',
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if (response.code === 0) {
                            zTree.addNodes(treeNode, {id:id, pId:treeNode.id, name:name, level: treeNode.level + 1});
                            newCount++;
                        } else {
                            alert(response.desc);
                        }
                    }
                });
                return false;
            });
        }
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_"+treeNode.tId).unbind().remove();
        }

        //编辑菜单
        $("#submit").click(function () {
            var data = vm.formData;
            if (data.hide === true) {
                data.hide = 1;
            } else {
                data.hide = 0;
            }
            $.ajax({
                type: "post",
                url: "get_menu",
                data: data,
                dataType: "json",
                success: function (data) {
                    if (data.code === 0) {
                        alert('保存成功');
                        getMenus();
                    } else {
                        alert('保存失败');
                    }
                }
            });
        });

        //删除菜单
        function zTreeBeforeRemove(treeId, treeNode) {
            if (treeNode.id == 0) {
                alert('根菜单无法删除');
                return false;
            }
            if (treeNode.isParent) {
                alert('请先删除子菜单');
                return false;
            }
            if (confirm("确认删除 菜单：" + treeNode.name + " 吗？")) {
                return destroyMenu(treeNode.id);
            } else {
                return false;
            }
        }

        //不能移动到2级菜单下
        function beforeDrop(treeId, treeNodes, targetNode, moveType) {
            if (targetNode.drop !== false) {
                setting.edit.drag.isMove = false;
                $.ajax({
                    type: 'post',
                    url: 'change_parent',
                    data: {
                        id: treeNodes[0].id,
                        targetId: targetNode.id
                    },
                    dataType: 'json',
                    success: function (response) {
                        getMenus();
                        setting.edit.drag.isMove = true;
                    }
                });
                return true;
            }
            return false;
        }

        function getMenus() {
            $.ajax({
                type: "GET",
                url: "get_menus",
                data: {},
                dataType: "json",
                success: function (zNodes) {
                    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
                }
            });
        }

        function destroyMenu(id) {
            $.ajax({
                type: 'post',
                url: 'destroy_menu',
                data: {id: id},
                dataType: 'json',
                success: function (response) {
                    if (response.code === 0) {
                        if (vm.formData.id == id) {
                            //删除当前选中菜单时，右侧界面恢复
                            vm.formData = {};
                        }
                        return true;
                    } else {
                        alert(response.desc);
                        return false;
                    }
                }
            })
        }
    </script>
@endsection

