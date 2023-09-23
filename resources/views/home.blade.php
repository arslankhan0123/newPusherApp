@extends('layouts.app')

@section('content')
<div class="row chat">
    <div class="col-md-3">
        <div class="users">
            <h5>All Users</h5>
            <ul class="list-group list-chat-item">
                @if($user->count())
                    @foreach($user as $item)
                    <li class="chat-user-list">
                        <a href="{{ route('message.conversation', $item->id) }}" style="text-decoration:none">
                            <div class="chat-image">
                                <div class="name-image bg-primary">
                                    {{makeImageFromName($item->name)}}
                                    <i class="fa fa-circle user-status-icon user-icon-{{ $item->id }}" title="away"></i>
                                </div>
                            </div>
                            {{$item->name}}
                        </a>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <h1>Message Selection</h1>
        <div class="lead">Select user to start chat</div>
    </div>
</div>
@endsection

<!-- Socket.io CDN -->
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js"></script>
<script src="jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(function() {
        let user_id = "{{ auth()->user()->id }}";
        let ip_address = '127.0.0.1';
        let socket_port = '8005';
        console.log(user_id);
        let socket = io(ip_address + ':' + socket_port);

        socket.on('connect', function() {
            // alert('here');
            socket.emit('user_connected', user_id);
        });

        socket.on('updateUserStatus', (data) => {
            console.log(data);
            let $userStatusIcon = $('.user-status-icon');
            $userStatusIcon.removeClass('text-success');
            $userStatusIcon.attr('title', 'Away');

            $.each(data, function(key, val) {
                if (val !== null && val !== 0) {
                    console.log(key);
                    let $userIcon = $(".user-icon-" + key);
                    console.log($userIcon)
                    $userIcon.addClass('text-success');
                    $userIcon.attr('title', 'Online');
                }
            });
        });
    });
</script>