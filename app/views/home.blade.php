@extends('layouts.main')

@section('content')
	@if(Auth::check())	
		<p>Hello, {{ Auth::user()->name }}</p>
	@else
		<p>You  are not signed in.</p>
	@endif 
@stop

<!-- <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>            
        </div>        
    </div> -->