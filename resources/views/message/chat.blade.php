<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Realtime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .chat-messages {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .chat-message {
            margin-bottom: 10px;
        }
        .username {
            font-weight: bold;
            color: #007bff;
        }
        .message-sent {
            text-align: right;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .message-received {
            text-align: left;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
    </style>
</head>
<body>


<div class="chat-container">
    <h2 class="text-center">Chat Realtime</h2>
    <div id="chat-messages" class="chat-messages"></div>
    <input type="text" name="username" 
       value="{{ Auth::check() ? Auth::user()->name : 'Khách' }}" 
       required id="username" 
       readonly class="form-control bg-light text-muted d-none" />

    <div class="input-group">
        <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
        <button id="sendMessageBtn" class="btn btn-primary">Gửi</button>
    </div>
</div>

<script>
    var pusher = new Pusher("f1f45151ac225b78b967", {
        cluster: "mt1"
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('App\\Events\\MessageSent', function(data) {
        const chatMessages = document.getElementById("chat-messages");
        const username = document.getElementById("username").value;

        const messageClass = data.username === username ? 'message-sent' : 'message-received';

        chatMessages.innerHTML += `
            <div class="chat-message ${messageClass}">
                <span class="username">${data.username}:</span> ${data.message}
            </div>
        `;
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });

    document.getElementById('sendMessageBtn').addEventListener("click", function() {
        const username = document.getElementById("username").value;
        const messageInput = document.getElementById('messageInput').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                username: username,
                message: messageInput
            })
        }).then(response => {
            document.getElementById('messageInput').value = '';
        }).catch(error => {
            console.error('Error sending message:', error);
        });
    });
</script>
</body>
</html>
