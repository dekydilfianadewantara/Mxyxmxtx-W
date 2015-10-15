@extends('backend.layouts.content')

@section('end-script')
    @parent
    <script type="text/javascript">
    $(document).ready(function() { 
        $(function () {
          $('.code_country').tooltip('show')
          $('.number').tooltip('show')
        })
    });
    </script>
@stop

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/users/insert', 'method'=>'POST'))}}
        <div class="box-body">  
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-4">
                        <label for="nama">Name</label>
                        <input type="text" name="name" value="{{Input::old('name')}}" class="form-control" id="name" placeholder="Name">
                        {{$errors->first('name','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-4">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="{{Input::old('email')}}" class="form-control" id="email" placeholder="Email Address">
                        {{$errors->first('email','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" value="{{Input::old('phone_number')}}" class="form-control" id="phone_number" placeholder="Phone Number">
                        <p class="help-block">example : <span class="code_country" data-toggle="tooltip" data-html="true" data-placement="top" title="Country Code">+26</span><span class="number" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Phone Number">3773182743</span></p>
                        {{$errors->first('phone_number','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" style="height:100px" rows="3" placeholder="Address ...">{{Input::old('address')}}</textarea>
                {{$errors->first('address','<p class="text-red">:message</p>')}}
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            @if(1 == Input::old('status'))
                                <option value="1" selected>Active</option>
                                <option value="0">Not Active</option>
                            @else
                                <option value="1">Active</option>
                                <option value="0" selected>Not Active</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="group">Group</label>
                        <select class="form-control" id="group" name="group">
                            <option value="">-Select Group-</option>
                            @foreach($group as $row)
                                @if($row->id == Input::old('group'))
                                    <option value="{{Input::old('group')}}" selected>{{$row->group_name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->group_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        {{$errors->first('group','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        {{$errors->first('password','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-6">
                        <label for="retype_password">Retype Password</label>
                        <input type="password" name="retype_password" class="form-control" id="retype_password" placeholder="Retype Password">
                        {{$errors->first('retype_password','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            
            
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/users')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary waiting">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop