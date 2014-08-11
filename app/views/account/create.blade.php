@extends('layouts.main')

@section('content')

	<form action="{{ URL::route('account-create') }}" method="post">

		<div class="field">
			Email: <input type="text" name="email" {{ Input::old('email') ? ' value="'. e(Input::old('email'))  .'"' : '' }}>
			@if($errors->has('email'))
				{{ $errors->first('email') }}
			@endif
		</div>

		<div class="field">
			Name: <input type="text" name="name" {{ Input::old('name') ? ' value="'. e(Input::old('name'))  .'"' : '' }}>
			@if($errors->has('name'))
				{{ $errors->first('name') }}
			@endif
		</div>

		<div class="field">
			Password: <input type="password" name="password">
			@if($errors->has('password'))
				{{ $errors->first('password') }}
			@endif
		</div>

		<div class="field">
			Password again: <input type="password" name="password_again">
			@if($errors->has('password_again'))
				{{ $errors->first('password_again') }}
			@endif
		</div>

		<input type="submit" value="Create account">

		{{ Form::token() }}
	</form>
@stop