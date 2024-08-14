<div class="card">
    <div class="card-header pb-0 border-0">
        <h5>People you might know</h5>
    </div>
    <div class="card-body">
        @foreach ($friendsToAdd as $user)
            <div class="hstack gap-2 mb-2 border p-2 d-flex justify-content-between">

                <a class="h4 mb-0" href="#!">{{ $user->name }}</a>
                {{-- Possible profile route --}}

                <form action="{{route('friend.add',$user->id)}}" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-primary w-1 h-1 " value="+">
                </form>

            </div>
        @endforeach

    </div>
</div>