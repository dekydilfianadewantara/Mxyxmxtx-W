@extends('backend.layouts.content')

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/permission/update/'.$permission->id, 'method'=>'POST'))}}
        <div class="box-body">
            <div class="form-group">
                <label for="parent">Parent</label>
                <select class="form-control parent_selected" id="parent" name="parent">
                    <option value="">-Select Parent-</option>
                    @foreach($parent as $row)
                        @if($row->id == $permission->id_parent)
                            <option value="{{$permission->id_parent}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group submenu">
                <label for="name"><a class="btn btn-primary btn-xs">Sub Menu <i class="fa fa-fw fa-sitemap"></i></a></label>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" value="{{$permission->name}}" class="form-control" id="name" placeholder="Menu Name">
                {{$errors->first('name','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="path">Path</label>
                <input type="text" name="path" value="{{$permission->url}}" class="form-control" id="path" placeholder="Menu Path">
                {{$errors->first('path','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="icon">Icon Class</label>
                <input type="text" name="icon" value="{{$permission->icon}}" class="form-control icon" id="icon" placeholder="Menu Icon">
                {{$errors->first('icon','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="body">Description</label>
                <textarea id="body" name="description" class="form-control" style="height:100px" placeholder="Menu Description">{{$permission->description}}</textarea>
                {{$errors->first('description','<p class="text-red">:message</p>')}}
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <a href="{{URL::to('admin/permission')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary waiting">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop