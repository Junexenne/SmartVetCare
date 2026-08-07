// Set the current user ID to your specific owner ID
// This uses the PHP session if available, otherwise defaults to 'OWN-00004'
const currentUserId = "<?php echo $_SESSION['user_id'] ?? 'OWN-00004'; ?>"; 

// 1. Listen to Real-time Messages using Firestore onSnapshot (Single Document per User)
function loadMessages() {
    const chatMessagesContainer = document.getElementById('chatMessages');
    
    // Path: conversations/{currentUserId} (Iisang document lang bawat user)
    db.collection("conversations")
      .doc(currentUserId)
      .onSnapshot((docSnapshot) => {
          chatMessagesContainer.innerHTML = ""; // Clear container to render new messages
          
          if (!docSnapshot.exists || !docSnapshot.data().messages || docSnapshot.data().messages.length === 0) {
              chatMessagesContainer.innerHTML = `<p style="text-align:center; color:#888; font-size:12px; margin-top:20px;">No messages yet. Start a conversation with the clinic support!</p>`;
              return;
          }

          const data = docSnapshot.data();
          const messages = data.messages || [];

          messages.forEach((msg) => {
              const isUser = msg.senderId === currentUserId;
              
              const messageDiv = document.createElement('div');
              messageDiv.classList.add('message', isUser ? 'user' : 'admin');
              messageDiv.textContent = msg.text;
              
              chatMessagesContainer.appendChild(messageDiv);
          });

          // Auto-scroll to the latest message
          chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
      });
}

// 2. Send Message to Firestore (Gamit ang arrayUnion para idagdag sa iisang dokumento)
const chatForm = document.getElementById('chatForm');
if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const inputField = document.getElementById('messageInput');
        const messageText = inputField.value.trim();
        
        if (!messageText) return;

        try {
            const userDocRef = db.collection("conversations").doc(currentUserId);

            // I-update ang iisang document gamit ang arrayUnion at set merge
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
            alert("Message failed to send. Please check your connection.");
        }
    });
}

// Run the function when the page loads
loadMessages();