@extends('backend.layouts.content')

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/product_category', 'method'=>'POST'))}}
        <div class="box-body">  
            <div class="form-group">
                <label for="nama">Name</label>
                <input type="text" name="name" value="{{Input::old('name')}}" class="form-control" id="name" placeholder="Name">
                {{$errors->first('name','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="prefix">Code Prefix</label>
                <input type="text" name="prefix" value="{{Input::old('prefix')}}" class="form-control" id="prefix" placeholder="Code Prefix">
                {{$errors->first('prefix','<p class="text-red">:message</p>')}}
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/product_category')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop