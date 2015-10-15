@extends('backend.layouts.content')

@section('end-script')
    @parent
    <script src="{{ asset('assets/js/AdminLTE/permission.js')}}" type="text/javascript"></script>
@stop

@section('header-content')
<div class="pull-right" style="margin-left:5px">
    <a href="{{URL::to('admin/permission/create')}}" class="btn btn-primary">Create</a>
</div>

{{Form::open(array('url'=>'admin/permission/submit','method'=>'POST','class'=>'pull-right'))}}
<div class="input-group">
  <select class="form-control" name="group" onchange="this.form.submit()">
      <option {{($group_session=='') ? 'selected' : ''}} value="">-Select Group-</option>
    @foreach($group as $row)
      <option value="{{$row->id}}" {{($row->id == $group_session) ? 'selected' : ''}}>{{$row->group_name}}</option>
    @endforeach
  </select>
</div>
{{Form::close()}}
@stop

@section('body-content') 
 
 @if(Session::has('permission'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('permission')}}.
    </div>
@endif

@if(Session::has('permission_errors'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Warning!</b> {{Session::get('permission_errors')}}.
    </div>
@endif
<!-- general form elements -->
<div class="box">

<div class="box-body">
  @if($group_session)
  {{Form::open(array('url'=>'admin/permission/save','method'=>'POST'))}}
   @foreach($controllers as $rows) 
        <?php $parent = Permission::isParent($rows->id); ?>
        <?php $menu = Permission::isMenu($rows->id); ?>
        @if($parent==1)
                <?php $cekbox1 = Permission::checkOnAccess($group_session, $rows->id); ?>
                @if($cekbox1)
                  <div class="callout callout-info">
                      <h4><i class="{{$rows->icon}}"></i> {{$rows->name}} <input type="checkbox" name="controller[]" class="parent_all root_{{$rows->id}}" data-id="{{$rows->id}}" value="{{$rows->id}}" checked/></h4>
                      <p>{{$rows->description}}.
                        <a href="{{URL::to('admin/permission/delete/'.$rows->id)}}" class="permision-edit btn btn-danger btn-xs delete" data-id="{{$rows->id}}"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                        <a href="{{URL::to('admin/permission/edit/'.$rows->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                      
                      </p> 
                  </div>
                @else
                  <div class="callout callout-info">
                      <h4><i class="{{$rows->icon}}"></i> {{$rows->name}} <input type="checkbox" name="controller[]" class="parent_all root_{{$rows->id}}" data-id="{{$rows->id}}" value="{{$rows->id}}" /></h4>
                      <p>{{$rows->description}}.
                        <a href="{{URL::to('admin/permission/delete/'.$rows->id)}}" class="permision-edit btn btn-danger btn-xs delete" data-id="{{$rows->id}}"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                        <a href="{{URL::to('admin/permission/edit/'.$rows->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a> 
                      </p> 
                  </div>
                @endif

                @foreach(Permission::cekchild($rows->id) as $value)
                  <?php $cekbox = Permission::checkOnAccess($group_session, $value->id); ?>
                  @if($cekbox)
                    <div class="callout callout-warning" style="margin-left: 20px;">
                        <h4><i class="fa fa-angle-double-right"></i> {{$value->name}} <input type="checkbox" name="controller[]" data-child="{{$rows->id}}" class="child parent_child_{{$rows->id}}" value="{{$value->id}}" checked/></h4>
                        <p>{{$value->description}}.
                          <a href="{{URL::to('admin/permission/delete/'.$value->id)}}" class="permision-edit btn btn-danger btn-xs delete" data-id="{{$value->id}}"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                          <a href="{{URL::to('admin/permission/edit/'.$value->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                        </p>
                    </div>
                  @else
                    <div class="callout callout-warning" style="margin-left: 20px;">
                        <h4><i class="fa fa-angle-double-right"></i> {{$value->name}} <input type="checkbox" name="controller[]" data-child="{{$rows->id}}" class="child parent_child_{{$rows->id}}" value="{{$value->id}}" /></h4>
                        <p>{{$value->description}}.
                          <a href="{{URL::to('admin/permission/delete/'.$value->id)}}" class="permision-edit btn btn-danger btn-xs delete" data-id="{{$value->id}}"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                          <a href="{{URL::to('admin/permission/edit/'.$value->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                        </p>
                    </div>
                  @endif
                @endforeach 
        @endif

        @if($menu)
          <?php $cekbox2 = Permission::checkOnAccess($group_session, $rows->id); ?>
          @if($cekbox2)
            <div class="callout callout-info">
                <h4><i class="{{$rows->icon}}"></i> {{$rows->name}} <input type="checkbox" name="controller[]" class="parent" value="{{$rows->id}}" checked/></h4>
                <p>{{$rows->description}}.
                  <a href="{{URL::to('admin/permission/delete/'.$rows->id)}}" class="permision-edit btn btn-danger btn-xs delete" data-id="{{$rows->id}}"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                  <a href="{{URL::to('admin/permission/edit/'.$rows->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                </p>
            </div>
          @else
            <div class="callout callout-info">
                <h4><i class="{{$rows->icon}}"></i> {{$rows->name}} <input type="checkbox" name="controller[]" class="parent" value="{{$rows->id}}"/></h4>
                <p>{{$rows->description}}.
                  <a href="{{URL::to('admin/permission/delete/'.$rows->id)}}" class="permision-edit btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Hapus</a>

                  <a href="{{URL::to('admin/permission/edit/'.$rows->id)}}" class="permision-edit btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                </p>
            </div>
          @endif
        @endif 
 @endforeach
 <div class="box-footer">
    <button type="submit" class="btn btn-primary">Save</button>
</div>
{{Form::close()}}
 @endif
</div>

</div>
@stop