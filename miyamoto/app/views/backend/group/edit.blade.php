@extends('backend.layouts.content')

@section('body-content')
<div class="box">
    {{Form::open(array('url'=>'admin/group/update/'.$group->id, 'method'=>'POST'))}}
        <div class="box-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="nama">Group name</label>
                        <input type="text" name="name" value="{{$group->group_name}}" class="form-control" id="name" placeholder="Group Name">
                        {{$errors->first('name','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="second_register">Second Register</label>
                        <select name="second_register" class="form-control" id="second_register">
                            <option value="0" @if($group->is_detail==0) selected @endif>Not Active</option>
                            <option value="1" @if($group->is_detail==1) selected @endif>Active</option>
                        </select>
                        {{$errors->first('name','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->

        <div class="box-footer">
            <a href="{{URL::to('admin/group')}}" class="btn btn-default">{{trans('button.bc')}}</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop