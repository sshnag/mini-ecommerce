@foreach($notifications as $notification)
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ $notification->data['message'] }}
        <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endforeach
