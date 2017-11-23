<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            {{var_dump($activeRoute)}}
            @foreach ($menus[0] as $key => $menu)
                @if (isset($menus[$menu['id']]) && count($menus[$menu['id']]) > 0)
                    {{--有子菜单--}}
                    <li class="treeview @unless ($key != 0) active @endunless">
                        <a href="{{url($menu['route'])}}">
                            <i class="fa {{$menu['icon']}}"></i> <span>{{$menu['name']}}</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @foreach ($menus[$menu['id']] as $key => $subMenu)
                                <li><a href="{{url($subMenu['route'])}}"><i class="fa {{$subMenu['icon']}}"></i> {{$subMenu['name']}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{--没有子菜单--}}
                    <li @unless ($key != 0) class="active" @endunless>
                        <a href="{{url($menu['route'])}}">
                            <i class="fa {{$menu['icon']}}"></i> <span>{{$menu['name']}}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>