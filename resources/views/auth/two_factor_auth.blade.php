@extends('layout.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6">
                <h3 class="text-center text-dark">Enter SMS code.</h3>
                <form action="{{route('login.tfa')}}" method="POST">
                    @csrf
                    <input maxlength="6" type="text" name="code" class="form-control mb-4">
                    @error('code')
                        <span class="text-danger danger">{{$message}}</span>
                    @enderror
                    <input type="submit" value="Send" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
@endsection