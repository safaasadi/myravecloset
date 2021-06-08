<style>
.chatbox {
    border-radius: 16px;
    padding-bottom: 1em;
    min-width: 320px;
    max-width: 380px;
    height: 480px;
    border-radius: 16px;
    padding-bottom: 1em;
    overflow: hidden;
}

.message-self {
    background-color: #6534ff;
    color: white;
    border-radius: 16px;
    display: inline-block;
    padding-left: 0.5em;
    padding-right: 0.5em;
}

.message-not-self {
    background-color: #e6e6e6;
    color: black;
    border-radius: 16px;
    display: inline-block;
    padding-left: 0.5em;
    padding-right: 0.5em;
}

.chat-nav {
    height: 16px;
    font-size: 1.2em;
    color: #c9c9c9;
    transition: all .2s;
}

.chat-nav:hover {
    color: black;
}

.chat-input {
    margin-left: 1em;
}

@media (max-width: 600px) {
    .chat-input {
        margin-left: 2em;
        width: 90%;
    }

    .chat-send {
        margin-left: -0.5em;
    }
}

.chat-input:hover {
    border: 1px solid #1467af;
}
</style>