@extends('layout.layout')

@section('content')

    <div class="row">
        <div class="col-3">
            
            @include('conversation.list')

        </div>
        <div class="col-6">
            @if (session()->has('success'))
                <span class="text-success">{{session()->get("success")}}</span>
            @endif
            @if (session()->has('error'))
                <span class="text-danger">{{session()->get("error")}}</span>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="container border p-2 mb-2">
                        <h3 class="m-0">{{$user->name}}</h3>
                    </div>

                    <div class="container border p-2 mb-2">
                        <h5 class="m-0">Change username.</h5>
                        {{-- form to change username --}}
                    </div>

                    <div class="container border p-2 mb-2">
                        <h5 class="m-0">Change email.</h5>
                        {{-- form to change email --}}
                    </div>

                    <div class="container border p-2 mb-2">
                        <h5 class="m-0">Change password.</h5>
                        {{-- form to change username --}}
                    </div>

                    <div class="container border p-2 mb-2">
                        <h5 class="m-0 mb-3">Turn on two-factor authetication.</h5>
                        <form action="{{ route('profile.settings.toggleTFA', auth()->user()->id ) }}" method="POST" >
                            @method("PATCH")
                            @csrf
                            
                            <input maxlength="9" type="text" name="phone_number" class="form-control mb-4" value="{{$user->phone_number}}">
                            @error('phone_number')
                                <span class="text text-danger">{{$message}}</span><br/><br/>
                            @enderror

                            <input type="checkbox" name="tfa_state" class="form-check-input me-2 mt-0" 
                            style="height: 32px; width: 32px" {{ auth()->user()->tfa_state ? 'checked' : '' }}>
                            <input type="submit" value="Save" class="btn btn-primary p-1 ps-2 pe-2">
                        </form>
                    </div>

                    <div class="container border p-2 mb-2">
                        <h5 class="m-0 mb-3">Extend you friend limit.</h5>
                        <form action="{{ route('checkout' ) }}" method="get" >
                            @csrf
                            <input type="submit" value="Extend" class="btn btn-primary p-1 ps-2 pe-2">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-3">

            @include('friends.friends_to_add')

        </div>
    </div>

@endsection