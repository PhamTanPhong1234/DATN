@extends('backend.layouts.app-admin')

@section('main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <style>
        .chat-container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
        }

        .input-group {
            display: flex;
        }

        .input-group input {
            flex: 1;
            margin-right: 10px;
        }

        .list-group-item img {
            margin-right: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
    </style>
</head>

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Danh sách danh mục tin nhắn</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <div class="row">
        <div class="col-md-4">
            <div class="tile">
                <h4>Đoạn chat</h4>
                <ul class="list-group" id="categories-list">
                    <li class="list-group-item text-center">Đang tải...</li>
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="chat-container">
                <h2 class="text-center">Chat</h2>
                <div id="chat-messages" class="chat-messages"></div>
                <div class="input-group">
                    <input type="text" id="messageInput" class="form-control" placeholder="Nhập tin nhắn...">
                    <button id="sendMessageBtn" class="btn btn-primary">Gửi</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        var pusher = new Pusher("f1f45151ac225b78b967", {
            cluster: "mt1"
        });
        var channel = pusher.subscribe('my-channel');
        channel.bind('App\\Events\\MessageSent', function(data) {
            const chatMessages = document.getElementById("chat-messages");
            chatMessages.innerHTML += `
            <div class="chat-message">
                <span class="username">${data.username}:</span> ${data.message}
            </div>
        `;
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
        sendMessageBtn.addEventListener("click", function() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message) {
                fetch("/send-message", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageInput.value = "";
                            fetchMessages(data.categoryId);
                        } else {
                            console.error("Error sending message:", data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error sending message:", error);
                    });
            }
        })

        function fetchCategories() {
            const categoriesList = document.getElementById("categories-list");
            fetch("/get/messages", {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let listItems = "";
                        data.forEach(category => {
                            listItems += `
                            <li class="list-group-item category-item" data-category-id="${category.user_id}" style="cursor: pointer;">
                                <img src="{{ asset('${category.user.avatar}') }}"" alt="Avatar">
                                ${category.user.name}
                            </li>
                        `;
                        });
                        categoriesList.innerHTML = listItems;
                        document.querySelectorAll('.category-item').forEach(item => {
                            item.addEventListener('click', function() {
                                const categoryId = this.getAttribute('data-category-id');
                                fetchMessages(categoryId);
                            });
                        });
                    } else {
                        categoriesList.innerHTML =
                            `<li class="list-group-item text-center">Không có danh mục nào.</li>`;
                    }
                })
                .catch(error => {
                    console.error("Error fetching categories:", error);
                    categoriesList.innerHTML =
                        `<li class="list-group-item text-center text-danger">Lỗi khi tải danh mục.</li>`;
                });
        }

        function fetchMessages(categoryId) {
            const chatMessages = document.getElementById("chat-messages");
            chatMessages.innerHTML = `<div class="text-center">Đang tải...</div>`;
            fetch(`/api/categories/${categoryId}/messages`, {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let messages = "";
                        data.forEach((message, index) => {
                            messages += `
                            <div class="chat-message">
                                <strong>${message.user.name}</strong>: ${message.message} <small class="text-muted">${new Date(message.created_at).toLocaleString()}</small>
                            </div>
                        `;
                        });
                        chatMessages.innerHTML = messages;
                    } else {
                        chatMessages.innerHTML =
                            `<div class="text-center">Không có tin nhắn trong danh mục này.</div>`;
                    }
                })
                .catch(error => {
                    console.error("Error fetching messages:", error);
                    chatMessages.innerHTML =
                        `<div class="text-center text-danger">Lỗi khi tải tin nhắn.</div>`;
                });
        }
        fetchCategories();
    });
</script>
@endsection