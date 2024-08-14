@auth
<div class="card-body ">
    @if ($conversation_users)
        <div class="header d-flex justify-content-between align-items-center border-bottom border-3 pb-3">
            <h4 class="m-0 p-0">Users: 
                @foreach ($conversation_users as $user)
                    {{$user->name.', '}}
                @endforeach
            </h4>
            <form action="{{route('conversation.add', $conversation)}}" method="get">
                @csrf
                <input type="submit" value="Edit" class="btn btn-primary">
            </form>
        </div>
    @endif

    <div id="chat" class="container p-0">
        
        @if ($conversation)
            <h3 class=" m-0 pb-3 pt-3">{{ $conversation->name }}</h3>
        @else
            <h3 class="p-2 m-0">Select a chat to view.</h3>
        @endif

        @forelse ($messages as $message)
            <p>{{ $message->user->name.': '.$message->content }}</p>
        @empty

        @endforelse
        
    </div>

    @if ($conversation)
        <form action="{{ route('conversations.message.store', $conversation->id) }}" method="POST" class="d-flex flex-column">
            @csrf
            <textarea class="form-control mb-4" name="content" autofocus></textarea>
            @error('content')
                <span class="text-danger danger">{{$message}}</span></br></br>
            @enderror
            <input class="btn btn-primary align-self-end" style="width: 100px" type="submit" value="Send">
            <input hidden id='chat_id' type="text" value='{{"chat".$conversation->id}}'>
        </form>
    @endif

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

    // Enable pusher logging - don't include this in production
    //Pusher.logToConsole = true;

    var pusher = new Pusher(env("PUSHER_APP_KEY"), {
        cluster: 'eu'
    });

    var channel = pusher.subscribe('chat');
    channel.bind(document.querySelector('#chat_id').value, function(data) {
        var message = document.createElement("p");
        message.innerText = data.username+": "+data.message;
        document.querySelector('#chat').append(message);
    });
    </script>
</div>

@endauth

@guest
    <h1 class="p-2 m-0">Login to access chat</h1>
@endguest