<div class="card mt-4">
    <div class="card-header pb-0 border-0">
        <h5>Friend requests.</h5>
    </div>
    <div class="card-body">
        @forelse ($friendRequestsReceived as $friendRequest)
            <div class="hstack gap-2 mb-2 border p-1 ps-2 pe-2 d-flex justify-content-between">

                <a class="h5 mb-0 overflow-hidden" href="#!">{{ $friendRequest->name }}</a>
                {{-- Possible profile route --}}

                <div class="d-flex flex-row">

                    <form action="{{route('friend.accept',$friendRequest)}}" method="POST"  class="w-100 me-1">
                        @csrf
                        @method("patch")
                        <input type="submit" class="btn btn-primary p-2 display-6" value="Accept">
                    </form>

                    <form action="{{route('friend.reject',$friendRequest)}}" method="POST" class="w-100">
                        @csrf
                        @method("delete")
                        <input type="submit" class="btn btn-primary p-2 display-6" value="Reject" class="w-100" style="width: 100%">
                    </form>
                </div>

            </div>
        @empty

            <h6>No pending requests</h6>

        @endforelse

        @if ($friendRequestsSent != [])
            <hr/>
        @endif

        @forelse ($friendRequestsSent as $friendRequest)

            <div class="hstack gap-2 mb-2 border p-1 ps-2 pe-2 d-flex justify-content-between">

                <a class="h5 mb-0 overflow-hidden" href="#!">{{ $friendRequest->name }}</a>
                {{-- Possible profile route --}}

                <div class="d-flex flex-row">

                    <form action="{{route('friend.cancel',$friendRequest)}}" method="POST"  class="w-100 me-1">
                        @csrf
                        @method("delete")
                        <input type="submit" class="btn btn-primary p-2 display-6" value="Cancel">
                    </form>
                </div>

            </div>
        @empty

            

        @endforelse
    </div>
</div>