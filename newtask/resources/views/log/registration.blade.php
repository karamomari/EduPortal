@extends('layout.app')

@section('title')Registration @endsection

@section('cont')

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<form method="POST" action="{{ route('login.registration') }}">
    @csrf


    <div class="form-group">
        <label for="username">User Name</label>
        <input type="text" class="form-control" id="username" name="userame"  placeholder="Enter User Name" required>
        <small id="username" class="form-text text-muted">User Name</small>
    </div>

    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required>
    </div>
    <div class="form-group ">
        <label for="exampleInputPassword1">Repeat Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="Repeat_password" placeholder="Repeat_password" required>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>


<script></script>

@endsection