@extends('layout.layout')

@section('content')

    <div class="row d.flex justify-content-center">
        <div class="col-6">
            @if (session()->has('success'))
                <span class="text-success">{{session()->get("success")}}</span>
            @endif
            @if (session()->has('error'))
                <span class="text-danger">{{session()->get("error")}}</span>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{route('conversation.update', $conversation)}}" method="POST">
                        @csrf

                        <h4>Name:</h4>
                        <input type="text" name="name" class="form-control mb-4" value="{{$conversation->name}}">
                        @error('name')
                            <span class="text text-danger">{{$message}}</span>
                        @enderror

                        <h4 class=" mb-4">Select friends to add to the conversation.</h4>
                        @forelse ($users as $user)
                            <h5 class="d.flex align-items-center">
                                <input type="checkbox" name="friends[]"{{$user->belongsToConversation($conversation) ? 'checked' : ''}} value="{{$user->id}}" class="form-check-input m-0" >
                                <span>{{$user->name}}</span>
                            </h5>
                        @empty
                            <h4>No friends found.</h4>
                        @endforelse
                        
                        <input type="submit" value="Create the conversation" class="btn btn-primary mt-4">
                        @error('friends')
                            <br/><span class="text text-danger">{{$message}}</span>
                        @enderror
                    </form> 
                </div>  
            </div>
        </div>
    </div>

@endsection