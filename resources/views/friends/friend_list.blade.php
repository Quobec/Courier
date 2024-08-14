<div class="card mt-4">
    <div class="card-header pb-0 border-0">
        <h5>Your friends.</h5>
    </div>

    <div class="card-body">
        @forelse ($friends as $friend)
            <div class="hstack gap-2 mb-2 border p-1 ps-2 pe-2 d-flex justify-content-between">

                <a class="h5 mb-0 overflow-hidden" href="#!">{{ $friend->name }}</a>
                {{-- Possible profile route --}}

            </div>
        @empty
            <h6>No friends found.</h6>
        @endforelse
    </div>

</div>