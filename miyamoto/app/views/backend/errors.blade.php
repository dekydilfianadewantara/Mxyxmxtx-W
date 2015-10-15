@extends('backend.layouts.content')

@section('body-content')
<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"> {{$code}}</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Oops! {{$error}}.</h3>
            <p>
                {{$note}}.
                Meanwhile, you may <a href="{{URL::to('admin/home')}}">return to dashboard</a> or try using the search form.
            </p>
            <form class='search-form'>
                <div class='input-group'>
                    <input type="text" name="search" class='form-control' placeholder="Search"/>
                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </div><!-- /.input-group -->
            </form>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->

</section><!-- /.content -->
@stop