<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    @foreach($get_menus as $get_menu)
    @if($get_menu->menu_group_id==1)
    <li class="nav-item">
        <a href="{{ route($get_menu->menu_link) }}">
            <i class="{{ $get_menu->menu_icon }}"></i>
            <span class="menu-title" data-i18n="">{{ $get_menu->menu_name }}  </span>
        </a>
    </li>
    @endif
    @endforeach
    @foreach($menu_groups as $menu_group)
    @if($menu_group->menu_group_id>1)
    <li class=" nav-item" value="{{ $menu_group->menu_group_id }}"><a href=""><i class="{{ $menu_group->menu_group_icon }}"></i><span class="menu-title" data-i18n="">{{ $menu_group->menu_group_name }}</span></a>
        <ul class="menu-content">
            <li><a class="menu-item" href="{{ route($menu_group->menu_link) }}"><i class="{{ $menu_group->menu_icon }}"></i>
                <span class="menu-title" data-i18n="">{{ $menu_group->menu_name }}  </span></a>
            </li>
        </ul>
    </li>
    @endif
    @endforeach
</ul>


/ * d.edit old* /

<ul class="sortable-row">
    @foreach($get_menus as $get_menu)
    @php 
    $menu_id=$get_menu->menu_id;
    @endphp
    @if(in_array($menu_id,$access_menu_arr))
    <label class="form-control bg-info text-white" for="{{$get_menu->menu_id}}"><ul class="sortableli"><input id="{{$get_menu->menu_id}}" type="checkbox" class="listdataid" checked="<?=$checked?>" name="list_data_array[]" value="{{$get_menu->menu_id}}"><b> {{$get_menu->menu_name}}</b></ul></label>
    @else
    <label class="form-control bg-info text-white" for="{{$get_menu->menu_id}}"><ul class="sortableli"><input id="{{$get_menu->menu_id}}" type="checkbox" class="listdataid" name="list_data_array[]" value="{{$get_menu->menu_id}}"><b> {{$get_menu->menu_name}}</b></ul></label>
    @endif
    @endforeach
</ul>

foreach($get_menus as $get_menu){
            if(in_array($get_menu->menu_id,$access_menu_arr)){
                $checked='checked';
            }
        }