@extends('layouts.app')
@section('title','主页')


@section('content')
@if (Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('shared._status_form')
        </section>
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
         <section class="stats">
          @include('shared._stats', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
    @include('shared._feed');
  @else
<div class="jumbotron">
	<h1>Hello!Come and join us!</h1>
	<p class="lead">Everything become from now!</p>
	<p>
	 <a href="{{route('signup')}}" class="btn btn-lg btn-success " role="button">现在注册</a>	
	</p>
</div>
@endif
@stop