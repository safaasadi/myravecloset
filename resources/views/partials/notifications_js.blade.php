<script type="text/javascript">
    // Subscribe to the channel we specified in our Laravel Event
    var notifications_channel = pusher.subscribe('Notification.{{ auth()->user()->id }}');
    // This function is called when a chat message has been received (We want this on the layout) (the full Laravel class)
    notifications_channel.bind('App\\Events\\NewNotification', function (data) {
        var count = parseInt(document.getElementById('nav-notifications').getAttribute('data-count-cart'));

        if(count < 1) {
            document.getElementById('nav-notifications').classList.add("btn-cart");
        }

        document.getElementById('nav-notifications').setAttribute('data-count-cart', count + 1); 
        
        var html = `<div class="item-product"><a href="${data.link}">${data.html}</a></div>`;

        $('#notifications-container').append(html);
    });
</script>