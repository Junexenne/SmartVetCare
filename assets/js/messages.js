// Set the current user ID to your specific owner ID
// This uses the PHP session if available, otherwise defaults to 'OWN-00004'
const currentUserId = "<?php echo $_SESSION['user_id'] ?? 'OWN-00004'; ?>"; 

// 1. Listen to Real-time Messages using Firestore onSnapshot (Single Document per User)
function loadMessages() {
    const chatMessagesContainer = document.getElementById('chatMessages');
    if (!chatMessagesContainer) return;
    
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
          
          // Sort messages by timestamp para laging maayos ang pagkakasunod-sunod
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
          // Auto-scroll to the latest message
          chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
      }, (error) => {
          console.error("Error loading messages: ", error);
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

// 3. Toggle Dropdown Menu para sa 3 Dots
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

// 4. Clinic Info Action gamit ang SweetAlert2 Modal
const btnClinicInfo = document.getElementById('btnClinicInfo');
if (btnClinicInfo) {
    btnClinicInfo.addEventListener('click', () => {
        Swal.fire({
            title: '<span style="color: #2F248F; font-weight: 700; font-size: 22px;">Furry Friends Animal Clinic</span>',
            html: `
                <div style="background: #f8faff; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: left; font-size: 14px; color: #334155; line-height: 1.6;">
                    <p style="margin: 0 0 8px 0;"><i class="fa-solid fa-clock" style="color: #2F248F; width: 20px;"></i> <b>Operating Hours:</b> 8:00 AM - 6:00 PM</p>
                    <p style="margin: 0;"><i class="fa-solid fa-location-dot" style="color: #2F248F; width: 20px;"></i> <b>Location:</b> Caloocan City</p>
                </div>
                <p style="margin-top: 14px; font-size: 13px; color: #64748b; font-style: italic;">Always ready to help your pets!</p>
            `,
            icon: 'info',
            iconColor: '#2F248F',
            confirmButtonText: 'Got it',
            confirmButtonColor: '#2F248F',
            buttonsStyling: true,
            customClass: {
                popup: 'custom-swal-popup',
                confirmButton: 'custom-swal-btn'
            }
        });
    });
}

// 5. Clear Chat Action gamit ang SweetAlert2 Confirm at Toast Notification
const btnClearChat = document.getElementById('btnClearChat');
if (btnClearChat) {
    btnClearChat.addEventListener('click', async () => {
        Swal.fire({
            title: '<span style="color: #1e293b; font-weight: 700; font-size: 20px;">Clear Chat History?</span>',
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

// 6. Action Buttons sa Ibaba (Attach, Health Record, Appointment)
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
// Run the function when the page loads
loadMessages();