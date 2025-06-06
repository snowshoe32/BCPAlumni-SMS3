


<button id="chat-button" class="btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle shadow-lg">
    💬
</button>

<!-- Chat Modal -->
<div id="chat-modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            
            <!-- Chat Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="chat-header">Select a contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex">
                <!-- Sidebar (Contacts List) -->
                <div class="col-4 p-3 border-end bg-light">
    <h6 class="fw-bold mb-3">Contacts List</h6>

    <!-- Search Bar -->
    <input type="text" id="search-input" class="form-control mb-3" placeholder="Search contacts...">

    <!-- Contacts List -->
    <div class="contact-list">
        
        <!-- MIS System -->
        <label class="fw-bold text-primary">MIS System</label>
        <ul id="mis-list" class="list-group mb-3">
            <li class="list-group-item">John Doe</li>
            <li class="list-group-item">Jane Smith</li>
            <li class="list-group-item">Michael Brown</li>
        </ul>

        <!-- Alumni System -->
        <label class="fw-bold text-primary">Alumni System</label>
        <ul id="alumni-list" class="list-group mb-3">
            <li class="list-group-item">Alice Johnson</li>
            <li class="list-group-item">Robert Wilson</li>
            <li class="list-group-item">Emily Davis</li>
        </ul>

        <!-- Library System -->
        <label class="fw-bold text-primary">Library System</label>
        <ul id="library-list" class="list-group">
            <li class="list-group-item">David Martinez</li>
            <li class="list-group-item">Sophia Lee</li>
            <li class="list-group-item">Daniel White</li>
        </ul>

    </div>
</div>


                <!-- Chat Area -->
                <div class="col-8 d-flex flex-column">
                    <!-- Chat Messages -->
                    <div id="chat-box" class="flex-grow-1 p-3 bg-white overflow-auto border rounded" style="height: 60vh;">
                        <div class="chat-messages"></div>
                    </div>

                    <!-- Input Box -->
                    <form id="frmSend_chat" class="p-3 border-top d-flex align-items-center">
                        <input type="file" id="file-input" name="file-input" class="d-none">
                        <label for="file-input" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-paperclip"></i>
                        </label>
                        <div id="file-preview" class="text-muted d-none"></div>
                        <input id="message-input" name="message-input" type="text" class="form-control flex-grow-1" placeholder="Type a message...">
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" id="sender_id" name="sender_id" value="<?=$_SESSION['id']?>">
                        <input type="hidden" id="reciever_id" name="reciever_id" value="">
                        <input type="hidden" name="systemFrom" value="alumni">
                        <input type="hidden" id="system" name="systemTo" value="">

                        <!-- Send Button -->
                        <button type="submit" id="btnSend_chat" class="btn btn-primary ms-2">
                            <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Bootstrap & Icons -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery to Open Chat Modal -->
<script>
$(document).ready(function() {
    $("#file-input").on("change", function() {
        let file = this.files[0]; // Get the first selected file
        let filePreview = $("#file-preview");

        if (file) {
            let fileName = file.name;
            let maxLength = 20; // Limit to 20 characters

            // Truncate filename if too long
            if (fileName.length > maxLength) {
                fileName = fileName.substring(0, maxLength) + "...";
            }

            filePreview.text("Attached: " + fileName);
            filePreview.removeClass("d-none"); // Show preview
        } else {
            filePreview.addClass("d-none"); // Hide preview if no file
        }
    });
});










$(document).ready(function(){
    $("#chat-button").click(function(){
        $("#chat-modal").modal("show");
    });

    fetchAlumni();
    fetchMis();
    fetchLibrary();


    $("#frmSend_chat").submit(function (e) {
    e.preventDefault();

    var fileInput = $("#file-input")[0].files; // Get files array
    var message = $("#message-input").val().trim();   
    var reciever_id = $("#reciever_id").val();   

    if (!reciever_id) {
        alertify.error("Select Contact First");
        return;
    }

    if (fileInput.length === 0 && !message) { 
        alertify.error("Attach a file or write a message first");
        return;
    }

    var formData = new FormData(this);
    formData.append('requestType', 'send_chat');



    $.ajax({
        type: "POST",
        url: "http://localhost/MIS/admin/backend/end-points/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response === "success") {
                $("#file-input").val("");  
                $("#message-input").val("");  
                $("#file-preview").html("").addClass("hidden"); 
            }
        }
    });

});
});
function fetchMis() {
    $.ajax({
        url: 'http://localhost/MIS/admin/backend/end-points/fetch_mis_user.php',
        type: 'GET',
        // dataType: 'json',
        success: function (data) {
            // console.log(data);
            let misList = $("#mis-list");

            // Clear existing lists
            misList.empty();

            if (data.length === 0) {
                misList.append(`<li class="text-gray-500">No users found</li>`);
                return;
            }
            data.forEach(user => {
                let userItem = `
                    <li class="target_chat_reciever p-2 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-100" data-system='mis' data-user_id=${user.id} data-user_name=${user.name}>
                        ${user.name}
                    </li>`;
                    misList.append(userItem);
            });
        },
    });
}


function fetchAlumni() {
    $.ajax({
        url: 'http://localhost/BCPAlumni-SMS3/alumni_system_api.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            let alumniList = $("#alumni-list");

            // Clear existing lists
            alumniList.empty();

            if (data.length === 0) {
                alumniList.append(`<li class="text-gray-500">No users found</li>`);
                return;
            }

            data.forEach(user => {
                let userItem = `
                    <li class="target_chat_reciever p-2 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-100" data-system='alumni' data-user_id=${user.id} data-user_name=${user.name}>
                        ${user.name}
                    </li>`;
                    // If user_type is not categorized, add them to a default list
                    alumniList.append(userItem);
               
            });
        },
    });
}


function fetchLibrary() {
    $.ajax({
        url: 'http://localhost/BCP_SMS3_Library/library_system_api.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            let libraryList = $("#library-list");

            // Clear existing lists
            libraryList.empty();

            if (data.length === 0) {
                libraryList.append(`<li class="text-gray-500">No users found</li>`);
                return;
            }

            data.forEach(user => {
                let userItem = `
                    <li class="target_chat_reciever p-2 bg-white rounded-lg shadow cursor-pointer hover:bg-gray-100" data-system='library' data-user_id=${user.user_id} data-user_name=${user.name}>
                        ${user.name}
                    </li>`;
                    // If user_type is not categorized, add them to a default list
                    libraryList.append(userItem);
               
            });
        },
    });
}


$(document).on('click', '.target_chat_reciever', function () {
    var user_id = $(this).data('user_id');
    var user_name = $(this).data('user_name');
    var system = $(this).data('system');
   $('#chat-header').text(user_name);
   $('#reciever_id').val(user_id);
   $('#system').val(system);
  
   fetchChatMessages(user_id);
   console.log('click');
});



function fetchChatMessages(receiver_id) {
    if (!receiver_id) return;

    var UserID = <?= json_encode($_SESSION['id']) ?>;

    let chatBox = $(".chat-messages");

    $.ajax({
        url: 'http://localhost/MIS/admin/backend/end-points/fetch_user_chat.php',
        type: "POST",
        data: { receiver_id: receiver_id },
        dataType: "json",
        success: function (response) {
            console.log(response.messages);

            chatBox.html("");

            if (response.status === "success" && response.messages.length > 0) {
                $.each(response.messages, function (index, message) {
                    let isSender = (message.sender_id == UserID);
                    let alignmentClass = isSender ? "justify-content-end" : "justify-content-start";
                    let bgColor = isSender ? "bg-primary text-white" : "bg-light text-dark";

                    let mediaHTML = "";

                    if (message.message_media) {
                        let filePath = `upload_files/${message.message_media}`;
                        let fileName = message.message_media.split("/").pop();

                        if (message.message_status == 2) {
                            // File is waiting for approval
                            mediaHTML = `
                                <div class="d-flex align-items-center gap-2 mt-2 text-muted">
                                    <i class="bi bi-file-earmark"></i>
                                    <span class="fst-italic">This file is waiting for approval</span>
                                </div>
                            `;
                        } else {
                            // File is approved and downloadable
                            mediaHTML = `
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <i class="bi bi-paperclip text-secondary"></i>
                                    <a href="http://localhost/MIS/assets/${filePath}" target="_blank" download="${fileName}" class="text-primary text-decoration-underline">${fileName}</a>
                                </div>
                            `;
                        }
                    }

                    let messageHTML = `
                        <div class="d-flex ${alignmentClass} mb-2">
                            <div class="${bgColor} p-3 rounded shadow-sm" style="max-width: 75%;">
                                <p class="mb-0 small">${message.message_text}</p>
                                ${mediaHTML}
                            </div>
                        </div>
                    `;
                    chatBox.append(messageHTML);
                });

                // Smooth scroll to the latest message
                chatBox.animate({ scrollTop: chatBox[0].scrollHeight }, 300);
            } else {
                chatBox.html(`<div class="text-center text-muted">No messages found. Start the conversation!</div>`);
            }

        },
        error: function () {
            chatBox.html(`<div class="text-center text-danger">Error fetching messages.</div>`);
            console.log("Error fetching messages.");
        }
    });
}



setInterval(function () {
        let receiver_id = $("#reciever_id").val();
        if (receiver_id) {
            fetchChatMessages(receiver_id);
        }
    }, 2000);




$(document).ready(function() {
    $('#search-input').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#mis-list li').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        $('#alumni-list li').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        $('#library-list li').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

function handleFileUpload() {
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    
    if (fileInput.files.length > 0) {
        filePreview.textContent = `Attached: ${fileInput.files[0].name}`;
        filePreview.classList.remove('hidden');
    } else {
        filePreview.classList.add('hidden');
    }
}


  $("#chat-button").click(function() {
        $("#chat-modal").fadeIn();
    });

    // Close Modal
    $("#Hide_create_report_modal").click(function() {
        $("#chat-modal").fadeOut();
    });

    // Close Modal when clicking outside the modal content
    $("#chat-modal").click(function(event) {
        if ($(event.target).is("#chat-modal")) {
            $("#chat-modal").fadeOut();
        }
    });
</script>


