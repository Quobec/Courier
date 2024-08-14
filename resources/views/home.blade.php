@extends('layout.layout')

@section('content')

    <div class="row">
        <div class="col-3">

            @include('conversation.list')
            @include('friends.friend_list')
            @include('friends.friend_requests')

        </div>

        <div class="col-6">

            @if (session()->has('success'))
                <span class="text-success">{{session()->get("success")}}</span>
            @endif
            @if (session()->has('error'))
                <span class="text-danger">{{session()->get("error")}}</span>
            @endif
            <div class="card">

                @include('conversation.show')
                
            </div>
        </div>

        <div class="col-3">
            @include('friends.friends_to_add')
        </div>
    </div>

@endsection