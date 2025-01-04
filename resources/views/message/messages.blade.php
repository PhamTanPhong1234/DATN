<div class="container-messages">
    <i id="box-chat" class="fa fa-comments"></i>
    <div id="chat-box" class="box-chat">
        <div class="chat-header d-flex justify-content-between align-items-center">
            <h5>Chat</h5>
            <button id="closeChatBtn" class="btn btn-sm btn-danger">X</button>
        </div>
        <div class="chat-content">
            <div class="messages" id="container-chat-messages" style="height: 300px; overflow-y: auto;"></div>
            <div class="d-flex align-items-center">
                <input type="text" id="messageInput" class="form-control mb-2 mr-2" placeholder="Nhập tin nhắn..." required>
                <input type="text" id="username" class="form-control mb-2 hidden" value="{{ Auth::user()->name ?? 'Khách' }}" readonly>
                <button id="sendMessageBtn" class="btn btn-primary mb-2">Gửi</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // var pusher = new Pusher("f1f45151ac225b78b967", {
        //     cluster: "mt1"
        // });

        // var channel = pusher.subscribe('my-channel');
        // channel.bind('App\\Events\\MessageSent', function(data) {
        //     const chatMessages = document.getElementById("container-chat-messages");
        //     chatMessages.innerHTML += `
        //         <div class="chat-message">
        //             <span class="username">${data.username}:</span> ${data.message}
        //         </div>
        //     `;
        //     chatMessages.scrollTop = chatMessages.scrollHeight;
        // });

        document.getElementById('sendMessageBtn').addEventListener("click", function(event) {
            event.preventDefault(); 

            const username = document.getElementById("username").value;
            const messageInput = document.getElementById('messageInput').value;

            if (!messageInput.trim()) {
                // Kiểm tra nếu không có tin nhắn nào được nhập
                console.log('Tin nhắn không được để trống');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Gửi tin nhắn đến server
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
            })
            .then(response => {
                if (response.ok) {
                    document.getElementById('messageInput').value = ''; // Xóa trường nhập liệu sau khi gửi
                } else {
                    console.error('Lỗi khi gửi tin nhắn:', response.statusText);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi tin nhắn:', error);
            });
        });

        // Đóng hộp chat khi bấm vào nút "X"
        document.getElementById('closeChatBtn').addEventListener("click", function() {
            document.getElementById('chat-box').style.display = 'none';
        });

        // Hiển thị hộp chat khi bấm vào biểu tượng "chat"
        document.getElementById('box-chat').addEventListener("click", function() {
            document.getElementById('chat-box').style.display = 'block';
        });
    })
</script>
