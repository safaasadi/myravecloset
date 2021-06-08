<script type="text/javascript">
    // Subscribe to the channel we specified in our Laravel Event
    var chat_channel = pusher.subscribe('Chat.{{ auth()->user()->id }}');
    // This function is called when a chat message has been received (We want this on the layout) (the full Laravel class)
    chat_channel.bind('App\\Events\\NewMessage', function (data) {
        var chatHidden = false;

        var classList = document.getElementById('chatbox-popup').className.split(/\s+/);
            for (var i = 0; i < classList.length; i++) {
                if (classList[i] === 'mfp-hide') {
                    chatHidden = true;
                }
            }

        if(chatHidden) {
            var count = parseInt(document.getElementById('nav-messages').getAttribute('data-count-wish'));
            if(count < 1) {
                document.getElementById('nav-messages').classList.add("btn-wish");
            }
            document.getElementById('nav-messages').setAttribute('data-count-wish', count + 1); 
        } else {
            var html = `
                <div class="row" style="padding-bottom: 0.5em;">
                    <div class="col-10">
                        <div class="row message-not-self">
                            <small>${data.message['message']}</small>
                        </div>
                    </div>
                </div>
            `;

            $('#chatbox-container').append(html);

            $('#chatbox-container').scrollTop($('#chatbox-container')[0].scrollHeight);

            $.ajax({
                url: "/mark-message-read/" + data.message['id'],
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
            }).done(function (resp) {

            });
        }
    });
</script>