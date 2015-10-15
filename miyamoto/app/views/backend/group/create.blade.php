@extends('backend.layouts.content')

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/group/store', 'method'=>'POST'))}}
        <div class="box-body">  
            <div class="form-group">
                <label for="nama">Group name</label>
                <input type="text" name="name" value="{{Input::old('name')}}" class="form-control" id="name" placeholder="Group Name">
                {{$errors->first('name','<p class="text-red">:message</p>')}}
            </div>  
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/group')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop