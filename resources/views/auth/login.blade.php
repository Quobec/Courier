@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6">
                @if (session()->has('success'))
                    <span class="text text-success">{{session()->get('success')}}</span>
                @endif
                
                <form class="form mt-5" action="{{ route('login.authenticate') }}" method="post">
                    @csrf

                    <h3 class="text-center text-dark">Login</h3>

                    <div class="form-group">
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
                    
                    @if (session()->has('error'))
                        <span class="text-danger">{{session()->get("error")}}</span>
                    @endif

                    <div class="form-group mt-4">
                        <input type="submit" name="submit" class="btn btn-dark btn-md" value="submit">
                    </div>

                    <div class="text-right mt-2">
                        <a href="{{route('register')}}" class="text-dark">Register here</a>
                    </div>
                </form>

                <a class="text-pimary" 
                    href="{{ route('google.login') }}">Login through Google  <span class="fa-brands fa-google"></span>
                </a>
                <br/>
                @if (session()->has('google-error'))
                    <span class="text text-danger">{{session()->get('error')}}</span>
                @endif

            </div>
        </div>
    </div>
@endsection