@extends('layouts.app')

<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@section('content')
<div class="row chat-row">
    <div class="col-md-3">
        <div class="users">
            <h5>Users</h5>

            <ul class="list-group list-chat-item">
                @if($users->count())
                    @foreach($users as $user)
                    <li class="chat-user-list
                                    @if($user->id == $friendInfo->id) active @endif">
                        <a href="{{ route('message.conversation', $user->id) }}" style="text-decoration:none">
                            <div class="chat-image">
                                <div class="name-image bg-primary">
                                    {!! makeImageFromName($user->name) !!}
                                    <i class="fa fa-circle user-status-icon user-icon-{{ $user->id }}" title="away"></i>
                                </div>
                            </div>
                            {{$user->name}}
                        </a>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>



    </div>

    <div class="col-md-9 chat-section">
        <div class="chat-header">
            <div class="chat-image">
                <div class="name-image bg-primary">
                    {{makeImageFromName($friendInfo->name)}}
                    <i class="fa fa-circle user-status-icon user-second-icon-{{ $friendInfo->id }}" title="away"></i>
                </div>
                <!-- <div class="chat-image font-weight-bold"> -->
                {{ $friendInfo->name }}
                <i class="fa fa-circle user-status-head" title="away" id="userStatusHead{{$friendInfo->id}}"></i>
                <!-- </div> -->
            </div>
        </div>

        <div class="chat-body" id="chatBody">
            <div class="message-listing" id="messageWrapper">
                <div class="row message align-items-center mb-2">
                    <div class="col-md-12 user-info">
                        <div class="chat-image">
                            <div class="name-image bg-primary">
                                {{makeImageFromName($user->name)}}
                            </div>
                            {{$user->name}}
                            <span class="small time text-grey-500" title="2020-05-05 10:30pm">10:30pm</span>
                        </div>
                        <!-- <div class="font-weight-bold">
                            Arslan khan
                            <span class="small time">10:30pm</span>
                        </div> -->
                    </div>
                    <div class="col-md-12 message-content">
                        <div class="message-text">
                            Message here
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-box">
            <div class="chat-input bg-white" id="chatInput" contenteditable="">

            </div>

            <div class="chat-input-toolbar">
                <button title="Add File" class="btn btn-light btn-sm btn-file-upload">
                    <i class="fa fa-paperclip"></i>
                </button> |

                <button title="Bold" class="btn btn-light btn-sm tool-items" onclick="document.execCommand('bold', false, '');">
                    <i class="fa fa-bold tool-icon"></i>
                </button>

                <button title="Italic" class="btn btn-light btn-sm tool-items" onclick="document.execCommand('italic', false, '');">
                    <i class="fa fa-italic tool-icon"></i>
                </button>
            </div>
        </div>
    </div>
</div>


@endsection

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>

<!-- Socket.io CDN -->
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.6.0/socket.io.min.js"></script>
<script src="jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(function() {
        let $chatInput = $(".chat-input");
        let $chatInputToolbar = $(".chat-input-toolbar");
        let $chatBody = $(".chat-body");
        let $messageWrapper = $("#messageWrapper");


        let user_id = "{{ auth()->user()->id }}";
        let ip_address = '127.0.0.1';
        let socket_port = '8005';
        let socket = io(ip_address + ':' + socket_port);
        let friendId = "{{ $friendInfo->id }}";

        socket.on('connect', function() {
            socket.emit('user_connected', user_id);
        });

        socket.on('updateUserStatus', (data) => {
            let $userStatusIcon = $('.user-status-icon');
            $userStatusIcon.removeClass('text-success');
            $userStatusIcon.attr('title', 'Away');

            $.each(data, function(key, val) {
                if (val !== null && val !== 0) {
                    let $userIcon = $(".user-icon-" + key);
                    $userIcon.addClass('text-success');
                    $userIcon.attr('title', 'Online');
                }
            });
        });

        $chatInput.keypress(function(e) {
            let message = $(this).html();
            if (e.which === 13 && !e.shiftKey) {
                $chatInput.html("");
                sendMessage(message);
                return false;
            }
        });



        function appendMessageToSender(message) {
            let name = '{{ $myInfo->name }}';
            let image = '{!! makeImageFromName($myInfo->name) !!}';

            let userInfo = '<div class="col-md-12 user-info">\n' +
                '<div class="chat-image">\n' + image +
                '</div>\n' +
                '\n' +
                '<div class="chat-name font-weight-bold">\n' +
                name +
                '<span class="small time text-gray-500" title="' + getCurrentDateTime() + '">\n' +
                getCurrentTime() + '</span>\n' +
                '</div>\n' +
                '</div>\n';

            let messageContent = '<div class="col-md-12 message-content">\n' +
                '                            <div class="message-text">\n' + message +
                '                            </div>\n' +
                '                        </div>';


            let newMessage = '<div class="row message align-items-center mb-2">' +
                userInfo + messageContent +
                '</div>';

            $messageWrapper.append(newMessage);
        }

        function appendMessageToReceiver(message) {
            let name = '{{ $friendInfo->name }}';
            let image = '{!! makeImageFromName($friendInfo->name) !!}';

            let userInfo = '<div class="col-md-12 user-info">\n' +
                '<div class="chat-image">\n' + image +
                '</div>\n' +
                '\n' +
                '<div class="chat-name font-weight-bold">\n' +
                name +
                '<span class="small time text-gray-500" title="' + dateFormat(message.created_at) + '">\n' +
                timeFormat(message.created_at) + '</span>\n' +
                '</div>\n' +
                '</div>\n';

            let messageContent = '<div class="col-md-12 message-content">\n' +
                '                            <div class="message-text">\n' + message.content +
                '                            </div>\n' +
                '                        </div>';


            let newMessage = '<div class="row message align-items-center mb-2">' +
                userInfo + messageContent +
                '</div>';

            $messageWrapper.append(newMessage);
        }

        socket.on("private-channel:App\\Events\\PrivateMessageEvent", function(message) {
            appendMessageToReceiver(message);
        });

        let $addGroupModal = $("#addGroupModal");
        $(document).on("click", ".btn-add-group", function() {
            $addGroupModal.modal();
        });

        $("#selectMember").select2();
    });
</script>





<script>
    $(function() {
        let user_id = "{{ auth()->user()->id }}";
        let ip_address = '127.0.0.1';
        let socket_port = '8005';
        // console.log(user_id);
        let socket = io(ip_address + ':' + socket_port);

        socket.on('connect', function() {
            // alert('here');
            socket.emit('user_connected', user_id);
        });

        socket.on('updateUserStatus', (data) => {
            // console.log(data);
            let $userStatusIcon = $('.user-status-icon');
            $userStatusIcon.removeClass('text-success');
            $userStatusIcon.attr('title', 'Away');

            $.each(data, function(key, val) {
                if (val !== null && val !== 0) {
                    console.log(key);
                    let $userIcon = $(".user-icon-" + key);
                    // console.log($userIcon)
                    $userIcon.addClass('text-success');
                    $userIcon.attr('title', 'Online');

                    let $userSecondIcon = $(".user-second-icon-" + key);
                    $userSecondIcon.addClass('text-success');
                    $userSecondIcon.attr('title', 'Online');

                }
            });
        });
    });
</script>
@endpush