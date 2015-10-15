@extends('backend.layouts.content')

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/clients/insert', 'method'=>'POST'))}}
        <div class="box-body">  
            <div class="form-group">
                <label for="nama">Name</label>
                <input type="text" name="name" value="{{Input::old('name')}}" class="form-control" id="name" placeholder="Name">
                {{$errors->first('name','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" value="{{Input::old('email')}}" class="form-control" id="email" placeholder="Email Address">
                {{$errors->first('email','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" value="{{Input::old('phone_number')}}" class="form-control" id="phone_number" placeholder="Phone Number">
                {{$errors->first('phone_number','<p class="text-red">:message</p>')}}
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" style="height:200px" rows="3" placeholder="Address ...">{{Input::old('address')}}</textarea>
                {{$errors->first('address','<p class="text-red">:message</p>')}}
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/clients')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop