@extends('layouts.app')
@section('title','主页')


@section('content')
<div class="jumbotron">
	<h1>Hello!Come and join us!</h1>
	<p class="lead">Everything become from now!</p>
	<p>
	 <a href="{{route('signup')}}" class="btn btn-lg btn-success " role="button">现在注册</a>	
	</p>
</div>
@stop