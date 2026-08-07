<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MESSAGE - Smart Vet Care</title>

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
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

        </div> <!-- Closing para sa main-content -->
    </div> <!-- Closing para sa dashboard -->

    <!-- Firebase v8 SDK Scripts -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-firestore.js"></script>

    <!-- Firestore Real-time Chat Logic -->
    <script>
        // 1. Ilagay dito ang tamang Firebase config mo mula sa Firebase Console
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

        // Kunin ang user ID mula sa session o gamitin ang OWN-00004
        const currentUserId = "<?php echo $_SESSION['user_id'] ?? 'OWN-00004'; ?>"; 

        // 2. Real-time Messages Listener
        function loadMessages() {
            const chatMessagesContainer = document.getElementById('chatMessages');
            if (!chatMessagesContainer) return;
            
            db.collection("conversations")
              .doc(currentUserId)
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
                      const isUser = data.senderId === currentUserId;
                      
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
                    await db.collection("conversations").doc(currentUserId).collection("messages").add({
                        senderId: currentUserId,
                        text: messageText,
                        timestamp: firebase.firestore.FieldValue.serverTimestamp()
                    });

                    await db.collection("conversations").doc(currentUserId).set({
                        participants: [currentUserId, "admin"],
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
    <script type="module" src="../assets/js/messages.js"></script>


</body>
</html>