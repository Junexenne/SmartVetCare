<?php
session_start();
// Kung walang session user_id, gagamitin ang OWN-00004 bilang default/fallback para lumitaw pa rin ang chat history
$userId = $_SESSION['user_id'] ?? 'OWN-00004';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>MESSAGE - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f8faff;
        }
        .dashboard {
            height: 100vh;
            height: 100dvh;
            display: flex;
            overflow: hidden;
            width: 100vw;
            box-sizing: border-box;
            position: relative;
        }
        .main-content {
            flex: 1;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
            min-width: 0;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
            box-sizing: border-box;
            background: #ffffff;
            margin: 15px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            padding: 10px 18px 8px 18px;
            gap: 8px;
        }
        .chat-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 50%, #38BDF8 100%);
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
            position: relative;
            z-index: 50;
        }
        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .chat-header .clinic-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            flex-shrink: 0;
        }
        .chat-header div h3 {
            margin: 0 0 2px 0;
            font-size: 14.5px;
            color: #ffffff;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-header div p {
            margin: 0;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-header-actions {
            position: relative;
            display: flex;
            gap: 12px;
            color: #ffffff;
            font-size: 18px;
            flex-shrink: 0;
            z-index: 60;
        }
        .chat-header-actions i {
            cursor: pointer;
            transition: color 0.2s;
            padding: 10px;
            pointer-events: auto !important;
        }
        .chat-header-actions i:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 45px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
            width: 160px;
            border: 1px solid #f1f5f9;
            z-index: 100;
            overflow: hidden;
        }
        .dropdown-menu.active {
            display: block;
        }
        .dropdown-item {
            padding: 12px 16px;
            font-size: 13px;
            color: #334155;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.2s;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background: #f8fafc;
            color: #2F248F;
        }
        .dropdown-item.danger {
            color: #ef4444;
        }
        .dropdown-item.danger:hover {
            background: #fef2f2;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            box-sizing: border-box;
            padding: 4px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #fdfcff;
            -webkit-overflow-scrolling: touch;
        }
        .message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }
        .message-wrapper.user {
            align-self: flex-end;
            align-items: flex-end;
        }
        .message-wrapper.admin {
            align-self: flex-start;
            align-items: flex-start;
        }
        .message {
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            box-sizing: border-box;
            display: inline-block;
        }
        .message-wrapper.user .message {
            background: #2F248F;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .message-wrapper.admin .message {
            background: #f1f5f9;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
            padding: 0 4px;
        }
        .suggested-chips-container {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 6px 2px;
            flex-shrink: 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .suggested-chips-container::-webkit-scrollbar {
            display: none;
        }
        .chip-btn {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            flex-shrink: 0;
            font-family: inherit;
        }
        .chip-btn:hover {
            background: #e2e8f0;
            color: #2F248F;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .chat-input-area {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 10px 0;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            gap: 12px;
            box-sizing: border-box;
        }
        .chat-tools {
            display: flex;
            gap: 12px;
            color: #94a3b8;
            font-size: 18px;
            padding-left: 4px;
            flex-shrink: 0;
        }
        .chat-tools i, .chat-tools label {
            cursor: pointer;
            transition: color 0.2s;
        }
        .chat-tools i:hover, .chat-tools label:hover {
            color: #2F248F;
        }
        .chat-input-area input[type="text"] {
            flex: 1;
            padding: 10px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 28px;
            font-size: 13px;
            outline: none;
            background: #f8fafc;
            transition: all 0.3s ease;
            box-sizing: border-box;
            min-width: 0;
        }
        .chat-input-area input[type="text"]:focus {
            border-color: #2563EB;
            background: #ffffff;
        }
        .chat-input-area button.send-btn {
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 100%);
            color: #ffffff;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }
        .chat-input-area button.send-btn:hover {
            opacity: 0.9;
        }
        .sidebar {
            z-index: 1100 !important;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block !important;
        }
        .custom-swal-popup {
            border-radius: 20px !important;
            padding: 24px !important;
            font-family: 'Poppins', sans-serif !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            width: 90% !important;
            max-width: 420px !important;
        }
        .swal2-styled.swal2-confirm, .swal2-styled.swal2-cancel {
            border-radius: 10px !important;
            padding: 10px 20px !important;
            font-weight: 500 !important;
            font-size: 13px !important;
        }
        @media (max-width: 768px) {
            .chat-container {
                margin: 0;
                border-radius: 0;
                border: none;
                height: 100%;
            }
            .chat-header {
                padding: 10px 14px;
                border-radius: 10px;
            }
            .chat-header div h3 {
                font-size: 13.5px;
            }
            .chat-header div p {
                font-size: 10.5px;
            }
            .chat-messages {
                padding: 10px;
            }
            .message-wrapper {
                max-width: 85%;
            }
            .chat-input-area {
                padding: 8px 0;
                border-radius: 0;
                gap: 8px;
            }
            .chat-tools {
                gap: 8px;
                font-size: 16px;
            }
            .chat-input-area input[type="text"] {
                padding: 10px 14px;
                font-size: 12px;
            }
            .chat-input-area button.send-btn {
                width: 36px;
                height: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="dashboard">
        <?php include("../includes/sidebar.php"); ?>
        <div class="main-content">
            <?php include("../includes/topbar.php"); ?>
            <div class="chat-container">
                <div class="chat-header">
                    <div class="chat-header-left">
                        <div class="clinic-icon">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div>
                            <h3>Furry Friends Clinic Support</h3>
                            <p><span style="color: #34d399;">●</span> Online | Always ready to help your pet</p>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        <i class="fa-solid fa-ellipsis-vertical" id="menuToggleBtn" title="More Options"></i>
                        <div class="dropdown-menu" id="chatDropdownMenu">
                            <div class="dropdown-item" id="btnClinicInfo">
                                <i class="fa-solid fa-circle-info"></i> Clinic Info
                            </div>
                            <div class="dropdown-item danger" id="btnClearChat">
                                <i class="fa-solid fa-trash-can"></i> Clear Chat
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chatMessages" class="chat-messages">
                    <!-- Dynamic Messages dito -->
                </div>
                <div class="suggested-chips-container">
                    <button type="button" class="chip-btn" data-text="Inquire about billing">
                        💳 Inquire about billing
                    </button>
                    <button type="button" class="chip-btn" data-text="Prescription refill">
                        💊 Prescription refill
                    </button>
                    <button type="button" class="chip-btn" data-text="Book appointment schedule">
                        📅 Book schedule
                    </button>
                    <button type="button" class="chip-btn" data-text="Emergency assistance">
                        🚨 Emergency assistance
                    </button>
                </div>
                <form id="chatForm" class="chat-input-area">
                    <div class="chat-tools">
                        <label for="attachFileInput" style="cursor: pointer;">
                            <i class="fa-solid fa-paperclip" title="Attach File"></i>
                        </label>
                        <input type="file" id="attachFileInput" style="display: none;">
                    </div>
                    <input type="text" id="messageInput" placeholder="Message clinic admin..." autocomplete="off" required>
                    <button type="submit" class="send-btn">
                        <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                    </button>
                </form>
            </div>
        </div> 
    </div> 
    <!-- Firebase v8 SDK Scripts -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-firestore.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const currentUserId = "<?php echo $userId; ?>";
        const firebaseConfig = {
            apiKey: "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM",
            authDomain: "furryfriendsanimalclinic-13da3.firebaseapp.com",
            projectId: "furryfriendsanimalclinic-13da3",
            storageBucket: "furryfriendsanimalclinic-13da3.firebasestorage.app",
            messagingSenderId: "214577366989",
            appId: "1:214577366989:web:60a23440fb34ed74f52684"
        };
        
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const db = firebase.firestore();

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggle = document.querySelector('#sidebarToggle') || document.querySelector('.fa-bars')?.closest('button') || document.querySelector('.fa-bars');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (sidebar) sidebar.classList.toggle('active');
                    if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
                });
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', () => {
                    if (sidebar) sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
            const chipButtons = document.querySelectorAll('.chip-btn');
            const messageInput = document.getElementById('messageInput');
            const chatForm = document.getElementById('chatForm');
            chipButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const text = button.getAttribute('data-text');
                    if (text && messageInput) {
                        messageInput.value = text;
                        messageInput.focus();
                        if (chatForm) {
                            chatForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        }
                    }
                });
            });
        });

        function loadMessages() {
            const chatMessagesContainer = document.getElementById('chatMessages');
            if (!chatMessagesContainer) return;
            
            db.collection("conversations")
              .doc(currentUserId)
              .onSnapshot((docSnapshot) => {
                  chatMessagesContainer.innerHTML = ""; 
                  
                  if (!docSnapshot.exists || !docSnapshot.data().messages || docSnapshot.data().messages.length === 0) {
                      chatMessagesContainer.innerHTML = `<p style="text-align:center; color:#888; font-size:12px; margin-top:20px;">No messages yet. Start a conversation with the clinic support!</p>`;
                      return;
                  }
                  const data = docSnapshot.data();
                  let messages = data.messages || [];
                  messages.sort((a, b) => {
                      const timeA = a.timestamp && a.timestamp.toDate ? a.timestamp.toDate() : new Date(a.timestamp || 0);
                      const timeB = b.timestamp && b.timestamp.toDate ? b.timestamp.toDate() : new Date(b.timestamp || 0);
                      return timeA - timeB;
                  });
                  messages.forEach((msg) => {
                      const isUser = msg.senderId === currentUserId;
                      let timeString = "";
                      if (msg.timestamp) {
                          const date = msg.timestamp.toDate ? msg.timestamp.toDate() : new Date(msg.timestamp);
                          if (!isNaN(date)) {
                              timeString = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                          }
                      }
                      const wrapperDiv = document.createElement('div');
                      wrapperDiv.classList.add('message-wrapper', isUser ? 'user' : 'admin');
                      const messageDiv = document.createElement('div');
                      messageDiv.classList.add('message');
                      messageDiv.textContent = msg.text;
                      wrapperDiv.appendChild(messageDiv);
                      if (timeString) {
                          const timeDiv = document.createElement('div');
                          timeDiv.classList.add('message-time');
                          timeDiv.textContent = timeString;
                          wrapperDiv.appendChild(timeDiv);
                      }
                      chatMessagesContainer.appendChild(wrapperDiv);
                  });
                  chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
              }, (error) => {
                  console.error("Error loading messages: ", error);
              });
        }

        const chatForm = document.getElementById('chatForm');
        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const inputField = document.getElementById('messageInput');
                const messageText = inputField.value.trim();
                
                if (!messageText) return;
                try {
                    const userDocRef = db.collection("conversations").doc(currentUserId);
                    await userDocRef.set({
                        participants: [currentUserId, "admin"],
                        lastMessage: messageText,
                        updatedAt: new Date(),
                        messages: firebase.firestore.FieldValue.arrayUnion({
                            senderId: currentUserId,
                            text: messageText,
                            timestamp: new Date()
                        })
                    }, { merge: true });
                    inputField.value = "";
                } catch (error) {
                    console.error("Error sending message: ", error);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Message failed to send. Please check your connection.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        }

        const menuToggleBtn = document.getElementById('menuToggleBtn');
        const chatDropdownMenu = document.getElementById('chatDropdownMenu');
        if (menuToggleBtn && chatDropdownMenu) {
            menuToggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                chatDropdownMenu.classList.toggle('active');
            });
            document.addEventListener('click', () => {
                chatDropdownMenu.classList.remove('active');
            });
        }

        const btnClinicInfo = document.getElementById('btnClinicInfo');
        if (btnClinicInfo) {
            btnClinicInfo.addEventListener('click', () => {
                Swal.fire({
                    title: '<span style="color: #2F248F; font-weight: 700; font-size: 20px;">Furry Friends Animal Clinic</span>',
                    html: `
                        <div style="background: #f8faff; padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: left; font-size: 13px; color: #334155; line-height: 1.6;">
                            <p style="margin: 0 0 8px 0;"><i class="fa-solid fa-clock" style="color: #2F248F; width: 20px;"></i> <b>Operating Hours:</b> 10:00 AM - 07:00 PM</p>
                            <p style="margin: 0;"><i class="fa-solid fa-location-dot" style="color: #2F248F; width: 20px;"></i> <b>Location:</b> 51 G De Jesus St. Bagong Barrio Caloocan City</p>
                            <p style="margin: 0 0 8px 0;"><i class="fa-solid fa-phone" style="color: #2F248F; width: 20px;"></i> <b>Contact Number:</b> 0925-8897-811</p>
                        </div>
                        <p style="margin-top: 12px; font-size: 12px; color: #64748b; font-style: italic;">Always ready to help your pets!</p>
                    `,
                    icon: 'info',
                    iconColor: '#2F248F',
                    confirmButtonText: 'Got it',
                    confirmButtonColor: '#2F248F',
                    customClass: {
                        popup: 'custom-swal-popup'
                    }
                });
            });
        }

        const btnClearChat = document.getElementById('btnClearChat');
        if (btnClearChat) {
            btnClearChat.addEventListener('click', async () => {
                Swal.fire({
                    title: '<span style="color: #1e293b; font-weight: 700; font-size: 18px;">Clear Chat History?</span>',
                    text: "Are you sure you want to delete all messages in this conversation? This action cannot be undone.",
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, clear it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'custom-swal-popup'
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const userDocRef = db.collection("conversations").doc(currentUserId);
                            await userDocRef.set({
                                messages: [],
                                lastMessage: ""
                            }, { merge: true });
                            
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Chat history cleared successfully.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } catch (error) {
                            console.error("Error clearing chat: ", error);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Failed to clear chat. Please try again.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
            });
        }

        const attachFileInput = document.getElementById('attachFileInput');
        if (attachFileInput) {
            attachFileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const messageInput = document.getElementById('messageInput');
                messageInput.value = `[Attachment: ${file.name}]`;
                messageInput.focus();
            });
        }
        loadMessages();
    </script>
</body>
</html>