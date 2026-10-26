<?php
session_start();
// Gamitin ang user_id galing sa session o ang default na OWN-00004
$userId = $_SESSION['user_id'] ?? 'OWN-00004';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>MESSAGE - Smart Vet Care</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        html, body {
            height: 100%;
            height: -webkit-fill-available;
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
            background: #f8faff;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
            min-width: 0;
            padding: 10px 20px 20px 20px !important;
            gap: 6px !important;
        }
        .topbar, header, .top-navbar {
            margin-bottom: 5px !important;
            flex-shrink: 0;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
            box-sizing: border-box;
            background: #ffffff;
            margin: 0 !important;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            padding: 12px 18px;
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
            z-index: 10;
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
            padding: 8px;
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
            padding: 10px 6px;
            display: flex;
            flex-direction: column;
            gap: 10px;
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
        .chat-input-area-wrapper {
            flex-shrink: 0;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
        .chat-input-area {
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            padding: 4px 0;
            gap: 12px;
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
        .chat-disclaimer {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.4;
            text-align: center;
            margin-top: 4px;
            padding: 0 4px;
            word-break: normal;
            overflow-wrap: break-word;
        }
        .chat-disclaimer i {
            margin-right: 3px;
        }
        .chat-disclaimer b {
            color: #64748b;
            white-space: nowrap;
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
                padding: 8px 10px calc(75px + env(safe-area-inset-bottom, 30px)) 10px;
            }
            .chat-header {
                padding: 10px 12px;
                border-radius: 12px;
                position: relative;
                top: 0;
                width: 100%;
                box-sizing: border-box;
            }
            .chat-header div h3 {
                font-size: 13px;
            }
            .chat-header div p {
                font-size: 10px;
            }
            .chat-header .clinic-icon {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }
            .chat-messages {
                padding: 8px 4px;
            }
            .message-wrapper {
                max-width: 85%;
            }
            .chat-tools {
                gap: 8px;
                font-size: 16px;
            }
            .chat-input-area input[type="text"] {
                padding: 9px 12px;
                font-size: 12px;
            }
            .chat-input-area button.send-btn {
                width: 34px;
                height: 34px;
            }
            .chat-disclaimer {
                font-size: 9.5px;
                line-height: 1.35;
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
                <!-- Suggested Chips Container -->
                <div class="suggested-chips-container">
                    <button type="button" class="chip-btn" data-text="Book an appointment schedule">
                        📅 Book schedule
                    </button>
                    <button type="button" class="chip-btn" data-text="Vaccination and deworming inquiry">
                        💉 Vaccination inquiry
                    </button>
                    <button type="button" class="chip-btn" data-text="Request for pet medical records">
                        🩺 Request medical record
                    </button>
                    <button type="button" class="chip-btn" data-text="Check confinement status of my pet">
                        🐾 Pet status update
                    </button>
                    <button type="button" class="chip-btn" data-text="Ask about clinic operating hours and location">
                        📍 Clinic hours & location
                    </button>
                </div>
                <div class="chat-input-area-wrapper">
                    <form id="chatForm" class="chat-input-area">
                        <input type="text" id="messageInput" placeholder="Message clinic admin..." autocomplete="off" required>
                        <button type="submit" class="send-btn">
                            <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                        </button>
                    </form>
                    <div class="chat-disclaimer">
                        <i class="fa-solid fa-clock"></i> Response times may vary as staff assist patients. For immediate emergency care, do not wait for a reply — call us directly at <b>0925-8897-811</b>.
                    </div>
                </div>
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
        let activeConvId = null;

        document.addEventListener('DOMContentLoaded', () => {
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

        // Robust Event Delegation para sa Mobile Sidebar / Burger Button
        document.addEventListener("click", function(e) {
            const menuBtn = e.target.closest("#mobileMenuBtn, #menuBtn, .mobile-menu-btn, .burger-btn, .fa-bars");
            const sidebar = document.querySelector(".sidebar") || document.getElementById("appSidebar");
            const overlay = document.getElementById("sidebarOverlay") || document.querySelector(".sidebar-overlay");

            if (menuBtn && sidebar) {
                e.stopPropagation();
                e.preventDefault();
                sidebar.classList.toggle("active");
                if (sidebar.classList.contains("active")) {
                    sidebar.style.setProperty("left", "0px", "important");
                    if (overlay) {
                        overlay.classList.add("active");
                        overlay.style.setProperty("display", "block", "important");
                    }
                } else {
                    sidebar.style.setProperty("left", "-270px", "important");
                    if (overlay) {
                        overlay.classList.remove("active");
                        overlay.style.setProperty("display", "none", "important");
                    }
                }
                return;
            }

            if (overlay && overlay.contains(e.target)) {
                if (sidebar) {
                    sidebar.classList.remove("active");
                    sidebar.style.setProperty("left", "-270px", "important");
                }
                overlay.classList.remove("active");
                overlay.style.setProperty("display", "none", "important");
            }
        });

        async function getOrCreateConversationDoc() {
            if (activeConvId) return activeConvId;
            try {
                const convQuery = db.collection("conversations").where("ownerId", "==", currentUserId);
                const snapshot = await convQuery.get();
                if (!snapshot.empty) {
                    activeConvId = snapshot.docs[0].id;
                    return activeConvId;
                } else {
                    const newDocRef = db.collection("conversations").doc();
                    activeConvId = newDocRef.id;
                    await newDocRef.set({
                        conversationId: activeConvId,
                        ownerId: currentUserId,
                        status: "Open",
                        unreadCount: 0,
                        assignedAdminId: null,
                        lastMessage: "",
                        createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    });
                    return activeConvId;
                }
            } catch (err) {
                console.error("Error getting/creating conversation doc: ", err);
                return "CONV-00001";
            }
        }

        async function loadMessages() {
            const chatMessagesContainer = document.getElementById('chatMessages');
            if (!chatMessagesContainer) return;
            
            const convId = await getOrCreateConversationDoc();
            
            db.collection("conversations")
              .doc(convId)
              .collection("messages")
              .orderBy("createdAt", "asc")
              .onSnapshot((snapshot) => {
                  chatMessagesContainer.innerHTML = ""; 
                  
                  if (snapshot.empty) {
                      chatMessagesContainer.innerHTML = `<p style="text-align:center; color:#888; font-size:12px; margin-top:20px;">No messages yet. Start a conversation with the clinic support!</p>`;
                      return;
                  }
                  
                  snapshot.forEach((docSnap) => {
                      const msg = docSnap.data();
                      const isUser = msg.senderId === currentUserId;
                      
                      let timeString = "";
                      if (msg.createdAt) {
                          const date = msg.createdAt.toDate ? msg.createdAt.toDate() : new Date(msg.createdAt);
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
                    const convId = await getOrCreateConversationDoc();
                    const messagesRef = db.collection("conversations").doc(convId).collection("messages");
                    const convDocRef = db.collection("conversations").doc(convId);
                    
                    await messagesRef.add({
                        text: messageText,
                        senderId: currentUserId,
                        senderRole: "PetOwner",
                        messageType: "text",
                        isRead: false,
                        createdAt: firebase.firestore.FieldValue.serverTimestamp()
                    });
                    await convDocRef.set({
                        lastMessage: messageText,
                        lastSenderId: currentUserId,
                        lastMessageAt: firebase.firestore.FieldValue.serverTimestamp(),
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
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
                            const convId = await getOrCreateConversationDoc();
                            const messagesRef = db.collection("conversations").doc(convId).collection("messages");
                            const snapshot = await messagesRef.get();
                            
                            const batch = db.batch();
                            snapshot.docs.forEach((doc) => {
                                batch.delete(doc.ref);
                            });
                            await batch.commit();
                            await db.collection("conversations").doc(convId).set({
                                lastMessage: "",
                                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
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

        loadMessages();
    </script>
</body>
</html>