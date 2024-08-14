@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6">
                <form class="form mt-5" action="{{ route('register.store') }}" method="post">
                    @csrf
                    <h3 class="text-center text-dark">Register</h3>

                    <div class="form-group">
                        <label for="name" class="text-dark">Name:</label><br>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    @error('name')
                        <span class="text-danger danger">{{$message}}</span>
                    @enderror

                    <div class="form-group mt-3">
                        <label for="email" class="text-dark">Email:</label><br>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    @error('email')
                        <span class="text-danger danger">{{$message}}</span>
                    @enderror

                    <div class="form-group mt-3">
                        <label for="password" class="text-dark">Password:</label><br>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    @error('password')
                        <span class="text-danger danger">{{$message}}</span>
                    @enderror

                    <div class="form-group mt-3">
                        <label for="password_confirmation" class="text-dark">Confirm Password:</label><br>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                    @error('password_confirmation')
                        <span class="text-danger danger">{{$message}}</span>
                    @enderror

                    <div class="form-group">
                        <label for="remember-me" class="text-dark"></label><br>
                        <input type="submit" name="submit" class="btn btn-dark btn-md" value="submit">
                    </div>
                    
                    <div class="text-right mt-2">
                        <a href="{{route('login')}}" class="text-dark">Login here</a>
                    </div>
                </form>
                
                <a class="text-pimary" 
                    href="{{ route('google.redirect') }}">Register through Google  <span class="fa-brands fa-google"></span>
                </a>
                <br/>
                @if (session()->has('google-error'))
                    <span class="text text-danger">{{session()->get('error')}}</span>
                @endif
            </div>
        </div>
    </div>
@endsection