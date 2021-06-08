@include('partials.messages.messages_push_js')

<script type="text/javascript">
$(document).ready(function() {
    $("#new-message-form-message").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            sendMessage();
        }
    });
});

function sendMessage() {
    var message =  $('#new-message-form-message').val();

    var html = `
            <div class="row" style="padding-bottom: 0.5em;">
                <div class="col-2"></div>
                <div class="col-10 text-right">
                    <div class="row message-self">
                        <small>${message}</small>
                    </div>
                </div>
            </div>
        `;

    $('#chatbox-container').append(html);

    $('#chatbox-container').scrollTop($('#chatbox-container')[0].scrollHeight);

    $('#new-message-form-message').val('');

    $.ajax({
        url: "{{ route('send-message') }}",
        data: {
            to_user_id: $('#new-message-form-send-to').val(),
            message: message,
            _token: '{{ csrf_token() }}'
        },
        type: 'POST',
    }).done(function (resp) {



    });
}

function loadConversation(other_user_id) {
    var user_id = "{{ auth()->user()->id }}";

    $('#chatbox-back').attr('hidden', false);
    $('#new-message-form-send-to').val(other_user_id);
    $('#new-message-form-message').val('');
    $('#new-message-form').attr('hidden', false);
    $('#new-message-form-message').focus();

    $.ajax({
        url: "{{ route('get-conversation') }}",
        type: 'POST',
        data: {
            'other_user_id': other_user_id,
            '_token': '{{ csrf_token() }}'
        },
    }).done(function (resp) {
        $('#chatbox-container').empty();

        var unread_messages = resp['unread_messages'];
        var count = parseInt(document.getElementById('nav-messages').getAttribute('data-count-wish'));
        if(unread_messages < 1) {
            document.getElementById('nav-messages').classList.remove("btn-wish");
        } else {
            document.getElementById('nav-messages').setAttribute('data-count-wish', unread_messages); 
        }
        
        var html = ``;

        $.each(resp['messages'], function( index, value ) {
            if(value['user_id'] != user_id) {
                html += `
                    <div class="row" style="padding-bottom: 0.5em;">
                        <div class="col-10">
                            <div class="row message-not-self">
                                <small>${value['message']}</small>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="row" style="padding-bottom: 0.5em;">
                        <div class="col-2"></div>
                        <div class="col-10 text-right">
                            <div class="row message-self">
                                <small>${value['message']}</small>
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        if(html == '') {
            $('#chatbox-title').text('New Message');
        } else {
            $('#chatbox-title').text(resp['other_user_username']);
        }

        $('#chatbox-container').append(html);

        $.magnificPopup.open({
            items: {
                src: '#chatbox-popup'
            },
            type: 'inline',
            showCloseBtn: false,
            mainClass: 'mfp-3d-unfold',
            midClick: true,
            closeOnBgClick: false,
            enableEscapeKey: true,
            fixedContentPos: true
        });

        $('#chatbox-container').scrollTop($('#chatbox-container')[0].scrollHeight);
    });
}

function loadConversations() {
    $('#chatbox-back').attr('hidden', true);
    $('#new-message-form').attr('hidden', true);
    $('#new-message-form-send-to').val('');
    $('#chatbox-title').text('Messages');

    $.ajax({
        url: "{{ route('get-conversations') }}",
        type: 'POST',
        data: {
            '_token': '{{ csrf_token() }}'
        },
    }).done(function (resp) {
        $('#chatbox-container').empty();
        var first = true;

        $.each(resp, function( key, value ) {
            if(first) {
                first = false;
            } else {
                $('#chatbox-container').append(`<hr />`);
            }

            var html = `
                <a href="javascript:loadConversation('${key}');" >
                    <div class="row">
                        <div class="col-2">
                            <img class="avatar-sm" style="margin-top: 0.5em;" src="${value['avatar']}">
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <p style="color: black;"><b style="padding-right: 0.5em;">${value['username']}</b><small style="color: #888;">11/06/2020</small></p>
                            </div>
                            <div class="row">
                                <small style="color: #888;"><i>${value['message']}</i></small>
                            </div>
                        </div>
                    </div>
                </a>
            `;

            $('#chatbox-container').append(html);
        });

        $.magnificPopup.open({
            items: {
                src: '#chatbox-popup'
            },
            type: 'inline',
            showCloseBtn: false,
            mainClass: 'mfp-3d-unfold',
            midClick: true,
            closeOnBgClick: false,
            enableEscapeKey: true,
            fixedContentPos: true
        });
    });
}

function closeChat() {
    $('#chatbox-container').empty();
    $('#new-message-form-send-to').val('');
    $('#new-message-form-message').val('');
    $('#new-message-form').attr('hidden', true);

    $.magnificPopup.close({
        items: {
            src: '#chatbox-popup'
        },
    });
}
</script>