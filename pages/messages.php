<?php
// PHP session ay pwede nang iwan o tanggalin, pero hinayaan na natin dito
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

    <style>
        /* Mobile-First Reset & Fullscreen Viewport Fixes */
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
            height: 100dvh; /* Dynamic viewport height para sa mobile browsers */
            display: flex;
            overflow: hidden;
            width: 100vw;
            box-sizing: border-box;
        }

        .main-content {
            flex: 1;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
            min-width: 0; /* Pinipigilan ang flexbox item na lumagpas sa screen width */
        }

        /* Chat Container Styling & Responsiveness */
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
        }

        .chat-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            gap: 12px;
        }

        .chat-header .clinic-icon {
            width: 42px;
            height: 42px;
            background: #ede8ff;
            color: #2F248F;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            flex-shrink: 0;
        }

        .chat-header div h3 {
            margin: 0;
            font-size: 15px;
            color: #173F81;
            font-weight: 700;
        }

        .chat-header div p {
            margin: 2px 0 0 0;
            font-size: 12px;
            color: #10b981; /* Online green color indicator */
            font-weight: 500;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
            box-sizing: border-box;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fdfcff;
            -webkit-overflow-scrolling: touch;
        }

        /* Message Bubbles Styling */
        .message {
            max-width: 75%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
            box-sizing: border-box;
        }

        .message.user {
            background: #2F248F;
            color: #ffffff;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .message.admin {
            background: #f1f5f9;
            color: #1e293b;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chat-input-area {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 14px 20px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            gap: 10px;
            box-sizing: border-box;
        }

        .chat-input-area input {
            flex: 1;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 13px;
            outline: none;
            background: #f8fafc;
            transition: all 0.3s ease;
            box-sizing: border-box;
            min-width: 0;
        }

        .chat-input-area input:focus {
            border-color: #2F248F;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(47, 36, 143, 0.08);
        }

        .chat-input-area button {
            background: #2F248F;
            color: #ffffff;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
            flex-shrink: 0;
        }

        .chat-input-area button:hover {
            background: #1f1863;
        }

        /* Sidebar Overlay para sa Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }

        /* Mobile Adjustments (Phones & Small Viewports) */
        @media (max-width: 768px) {
            .chat-container {
                margin: 0;
                border-radius: 0;
                border: none;
                height: 100%;
            }

            .chat-header {
                padding: 12px 15px;
                border-radius: 0;
            }

            .chat-input-area {
                padding: 10px 15px;
                border-radius: 0;
            }

            .message {
                max-width: 85%;
            }

            /* Responsive Sidebar Styles para sa Mobile */
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
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
                <!-- Header -->
                <div class="chat-header">
                    <div class="clinic-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <h3>Furry Friends Clinic Support</h3>
                        <p>Online | Always ready to help your pet</p>
                    </div>
                </div>

                <!-- Chat Messages Box -->
                <div id="chatMessages" class="chat-messages">
                    <!-- Messages from Firestore will load dynamically here -->
                </div>

                <!-- Chat Input Form -->
                <form id="chatForm" class="chat-input-area">
                    <input type="text" id="messageInput" placeholder="Type your message here..." autocomplete="off" required>
                    <button type="submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div> 
    </div> 

    <!-- Firebase v8 SDK Scripts -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-firestore.js"></script>

    <!-- Firestore Real-time Chat Logic -->
    <script>
        // Script para sa Mobile Sidebar Toggle gamit ang hamburger button at overlay
        document.addEventListener("DOMContentLoaded", () => {
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar'); 
            const overlay = document.getElementById('sidebarOverlay');

            if (mobileBtn && sidebar && overlay) {
                mobileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });

        // 1. Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM",
            authDomain: "furryfriendsanimalclinic-13da3.firebaseapp.com",
            projectId: "furryfriendsanimalclinic-13da3",
            storageBucket: "furryfriendsanimalclinic-13da3.firebasestorage.app",
            messagingSenderId: "214577366989",
            appId: "1:214577366989:web:60a23440fb34ed74f52684"
        };

        // Initialize Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const db = firebase.firestore();

        // Kunin ang ID mula sa localStorage (ownerId o userUID) para dynamic kung sino ang naka-login
        window.currentUserId = localStorage.getItem("ownerId") || localStorage.getItem("userUID");

        // Kung walang naka-login, i-redirect pabalik sa login page
        if (!window.currentUserId || localStorage.getItem("isLoggedIn") !== "true") {
            window.location.href = 'login-user.php';
        }

        // 2. Real-time Messages Listener
        function loadMessages() {
            const chatMessagesContainer = document.getElementById('chatMessages');
            if (!chatMessagesContainer) return;
            
            db.collection("conversations")
              .doc(window.currentUserId)
              .collection("messages")
              .orderBy("timestamp", "asc")
              .onSnapshot((snapshot) => {
                  chatMessagesContainer.innerHTML = "";
                  
                  if (snapshot.empty) {
                      chatMessagesContainer.innerHTML = `<p style="text-align:center; color:#888; font-size:12px; margin-top:20px;">No messages yet. Start a conversation with the clinic support!</p>`;
                      return;
                  }

                  snapshot.forEach((docSnap) => {
                      const data = docSnap.data();
                      const isUser = data.senderId === window.currentUserId;
                      
                      const messageDiv = document.createElement('div');
                      messageDiv.classList.add('message', isUser ? 'user' : 'admin');
                      messageDiv.textContent = data.text;
                      
                      chatMessagesContainer.appendChild(messageDiv);
                  });

                  chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
              }, (error) => {
                  console.error("Error loading messages: ", error);
              });
        }

        // 3. Send Message Function
        const chatForm = document.getElementById('chatForm');
        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const inputField = document.getElementById('messageInput');
                const messageText = inputField.value.trim();
                
                if (!messageText) return;

                try {
                    await db.collection("conversations").doc(window.currentUserId).collection("messages").add({
                        senderId: window.currentUserId,
                        text: messageText,
                        timestamp: firebase.firestore.FieldValue.serverTimestamp()
                    });

                    await db.collection("conversations").doc(window.currentUserId).set({
                        participants: [window.currentUserId, "admin"],
                        lastMessage: messageText,
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    }, { merge: true });

                    inputField.value = "";
                } catch (error) {
                    console.error("Error sending message: ", error);
                    alert("Message failed to send. Please check your connection.");
                }
            });
        }

        // Run on load
        loadMessages();
    </script>

</body>
</html>