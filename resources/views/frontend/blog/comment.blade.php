<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5 comment-area">
        <h2 class="comment-heading mb-4"><span id="comment-count">0</span> Bình luận</h2>
        <div class="review-wrapper" id="review-wrapper"></div>

        <div class="new-comment mt-4">
            <textarea class="form-control mb-2" id="new-comment-content" rows="3" placeholder="Viết bình luận mới"></textarea>
            <button class="btn btn-primary" id="submit-new-comment">Gửi bình luận</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');

            function loadComments() {
                fetch(`/get-comments?id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const reviewWrapper = document.getElementById("review-wrapper");
                        const commentCount = document.getElementById("comment-count");
                        reviewWrapper.innerHTML = '';
                        commentCount.textContent = data.comments.length;
                        if (data.comments && data.comments.length) {
                            data.comments.forEach(comment => {
                                const review = document.createElement("div");
                                review.classList.add("single-review", "d-flex", "mb-4", "p-3",
                                    "border",
                                    "rounded");
                                review.setAttribute("data-comment-id", comment.id);
                                review.innerHTML = `
                                    <div class="review-img me-1">
                                        <img src="${comment.userImage}" alt="" class="rounded-circle" width="50" height="50" />
                                    </div>
                                    <div class="review-content w-100">
                                        <div class="review-top-wrap d-flex justify-content-between align-items-center">
                                            <div class="review-left flex gap-2">
                                             <span class="mb-1">${comment.username}</span>
                                            <span class="text-muted small">${comment.createdAt}</span>
                                            </div>
                                            <div class="review-right">
                                                <button class="btn btn-link reply-btn text-primary p-0" data-comment-id="${comment.id}">Trả lời</button>
                                            </div>
                                        </div>
                                        <div class="review-bottom mt-2">
                                      <p class="mb-0 text-dark fw-normal">${comment.content}</p>
                                       <div class="review-right">
    <button 
        class="btn btn-link reply-btn text-danger p-0 no-underline" 
        data-comment-id="${comment.id}">
        Xem tất cả ${comment.replies.length} phản hồi
    </button>
</div>

                                        </div>
                                        <div class="replies collapse" id="replies-${comment.id}">
                                            ${comment.replies.slice(0, 3).map(reply => `
                                                                <div class="single-reply ms-3 d-flex mt-2 p-1 border rounded">
                                                                    <div class="review-img me-3">
                                                                        <img src="${reply.userImage}" alt="" class="rounded-circle" width="40" height="40" />
                                                                    </div>
                                                                    <div class="review-content w-100">
                                                                        <div class="review-left">
                                                                            <h5 class="mb-1">${reply.username}</h5>
                                                                            <span class="text-muted small">${reply.createdAt}</span>
                                                                        </div>
                                                                        <div class="review-bottom mt-2">
                                                                            <p class="mb-0">${reply.content}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            `).join('')}
                                            ${comment.replies.length > 3 ? `<button class="btn btn-link text-primary p-0 more-replies-btn" data-comment-id="${comment.id}">Xem thêm bình luận...</button>` : ''}
                                        </div>
                                       <div class="reply-form ms-3 mt-5" id="reply-form-${comment.id}" style="display:none;">
    <textarea 
        id="reply-content-${comment.id}" 
        class="form-control mb-2" 
        rows="2" 
        placeholder="Viết trả lời cho @${comment.username}"></textarea>
    <button 
        class="btn btn-success btn-sm submit-reply" 
        data-comment-id="${comment.id}">
        Gửi
    </button>
</div>

                                    </div>
                                `;
                                reviewWrapper.appendChild(review);
                            });
                        } else {
                            const noComments = document.createElement("p");
                            noComments.classList.add("text-muted");
                            noComments.textContent = "Chưa có bình luận nào.";
                            reviewWrapper.appendChild(noComments);
                        }
                        attachReplyButtonEvents();
                        attachMoreRepliesButtonEvents();
                    })
                    .catch(error => console.error("Error loading comments:", error));
            }

            function attachReplyButtonEvents() {
                const replyButtons = document.querySelectorAll('.reply-btn');
                replyButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        const commentId = this.getAttribute('data-comment-id');
                        const replyForm = document.getElementById(`reply-form-${commentId}`);
                        const replies = document.getElementById(`replies-${commentId}`);
                        replyForm.style.display = 'block';
                        const collapse = new bootstrap.Collapse(replies, {
                            toggle: true
                        });
                    });
                });
                const submitReplyButtons = document.querySelectorAll('.submit-reply');
                submitReplyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const content = document.getElementById(`reply-content-${commentId}`)
                            .value;
                        submitReply(commentId, content);
                    });
                });
            }

            function attachMoreRepliesButtonEvents() {
                const moreRepliesButtons = document.querySelectorAll('.more-replies-btn');
                moreRepliesButtons.forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.preventDefault();
                        const commentId = this.getAttribute('data-comment-id');
                        const repliesDiv = document.getElementById(`replies-${commentId}`);
                        const comment = data.comments.find(comment => comment.id == commentId);
                        repliesDiv.innerHTML = comment.replies.map(reply => `
                            <div class="single-reply ms-2 d-flex mt-2 p-1 border rounded">
                                <div class="review-img me-2">
                                 <img src="${reply.userImage ? reply.userImage : 'https://cellphones.com.vn/sforum/wp-content/uploads/2023/10/avatar-trang-4.jpg'}" alt="" class="rounded-circle" width="40" height="40" />
                                </div>
                               <div class="review-content w-100 mt-3">
    <div class="review-left">
        <h5 class="mb-1">${reply.username}</h5>
        <span class="text-muted small">${reply.createdAt}</span>
    </div>
    <div class="review-bottom mt-2">
        <p class="mb-0">${reply.content}</p>
    </div>
</div>
                            </div>
                        `).join('');
                    });
                });
            }

            function submitReply(commentId, content) {
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id');
                const replyData = {
                    news_id: id,
                    comment_id: commentId,
                    content: content
                };
                fetch("/save-reply", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(replyData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message || "Đánh giá của bạn đã được gửi thành công!",
                                "Thành công", {
                                    timeOut: 3000,
                                    hideDuration: 1000,
                                    extendedTimeOut: 1000,
                                });
                            loadComments();
                        } else {
                            toastr.error(data.message || "Có lỗi xảy ra khi gửi trả lời.", "Lỗi", {
                                timeOut: 3000,
                                hideDuration: 1000,
                                extendedTimeOut: 1000,
                            });
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

            function submitNewComment() {
                const content = document.getElementById('new-comment-content').value;
                if (!content.trim()) {
                    alert("Vui lòng nhập nội dung bình luận!");
                    return;
                }
                const newCommentData = {
                    content: content,
                    news_id: id
                };
                fetch("/save-comment", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(newCommentData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message || "Bình luận của bạn đã được gửi thành công!",
                                "Thành công", {
                                    timeOut: 3000,
                                    hideDuration: 1000,
                                    extendedTimeOut: 1000,
                                });
                            document.getElementById('new-comment-content').value = '';
                            loadComments();
                        } else {
                            toastr.error(data.message || "Có lỗi xảy ra khi gửi bình luận.", "Lỗi", {
                                timeOut: 3000,
                                hideDuration: 1000,
                                extendedTimeOut: 1000,
                            });
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
            document.getElementById('submit-new-comment').addEventListener('click', submitNewComment);
            loadComments();
        });
    </script>
</body>

</html>
