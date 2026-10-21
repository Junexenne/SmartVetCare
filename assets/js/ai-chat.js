import { GoogleGenAI } from "https://esm.run/@google/genai";
import { db } from "./firebase-config.js";
import { 
    doc, 
    getDoc, 
    setDoc, 
    updateDoc, 
    arrayUnion, 
    serverTimestamp 
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

// Initialize Google GenAI with API Key
const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6LfzoC-9lXu7Ff5T8hmg7809GWwRzEIihs9uUIpV42uzA";
const ai = new GoogleGenAI({ apiKey: apiKey });
const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";

// Listahan ng mga active Gemini models na susubukan nang sunod-sunod
const modelCandidates = [
    'gemini-3.8-flash',
    'gemini-2.0-flash'
];

// 1. Load Chat History mula sa iisang Document gamit ang Array
async function loadChatHistory() {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;
    try {
        const docRef = doc(db, "ai_chats", currentOwnerId);
        const docSnap = await getDoc(docRef);
        
        chatMessages.innerHTML = ""; 
        
        if (docSnap.exists()) {
            const data = docSnap.data();
            const messageList = data.messages || [];
            
            messageList.forEach((msg) => {
                appendMessageUI(msg.sender, msg.text, false);
            });
        } else {
            appendMessageUI('ai', '<i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 6px;"></i> Hello, Smart Vet Fam! How can I help you today with your pets or booking an appointment?', false);
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (error) {
        console.error("Error loading chat history:", error);
    }
}

// 2. Append Message to UI na may tamang Spacing at Nilinis na Markdown Bold Tags
function appendMessageUI(sender, text, scroll = true) {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;
    const wrapperDiv = document.createElement("div");
    wrapperDiv.classList.add("message-wrapper", sender === 'ai' ? 'ai' : 'user');
    
    const msgDiv = document.createElement("div");
    msgDiv.classList.add("message");
    msgDiv.style.whiteSpace = "pre-wrap";
    
    if (sender === 'ai' && text.includes('Hello, Smart Vet Fam')) {
        msgDiv.innerHTML = text;
    } else if (sender === 'ai') {
        // Linisin at i-convert ang **text** patungong <b>text</b> para maging malinis tingnan
        let formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
            .replace(/\*/g, '•');
        msgDiv.innerHTML = formattedText;
    } else {
        msgDiv.textContent = text;
    }
    
    wrapperDiv.appendChild(msgDiv);
    chatMessages.appendChild(wrapperDiv);
    
    if (scroll) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// 3. I-save o I-update ang Array sa iisang Document lang sa Firestore
async function saveMessageToFirestore(sender, text) {
    try {
        const docRef = doc(db, "ai_chats", currentOwnerId);
        const docSnap = await getDoc(docRef);
        const newMessage = {
            sender: sender,
            text: text,
            timestamp: new Date().toISOString()
        };
        if (!docSnap.exists()) {
            await setDoc(docRef, {
                userId: currentOwnerId,
                messages: [newMessage],
                updatedAt: serverTimestamp()
            });
        } else {
            await updateDoc(docRef, {
                messages: arrayUnion(newMessage),
                updatedAt: serverTimestamp()
            });
        }
    } catch (error) {
        console.error("Error saving message to Firestore:", error);
    }
}

// 4. Primary Send Function na may Inayos na Loading Layout
async function handleSendMessage(messageText) {
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");
    const promptText = messageText || (userInput ? userInput.value.trim() : "");
    if (!promptText) return;
    
    appendMessageUI("user", promptText);
    await saveMessageToFirestore("user", promptText);
    if (userInput) userInput.value = "";
    
    const loadingId = "loading-" + Date.now();
    const loadingWrapper = document.createElement("div");
    loadingWrapper.classList.add("message-wrapper", "ai");
    loadingWrapper.id = loadingId;
    
    const loadingDiv = document.createElement("div");
    loadingDiv.classList.add("message");
    loadingDiv.style.whiteSpace = "nowrap";
    loadingDiv.style.minWidth = "fit-content";
    loadingDiv.innerHTML = `<span style="display: inline-flex; gap: 4px; align-items: center; white-space: nowrap;"><i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 4px;"></i> Thinking<span style="animation: blink 1.4s infinite both;">.</span><span style="animation: blink 1.4s infinite both .2s;">.</span><span style="animation: blink 1.4s infinite both .4s;">.</span></span>`;
    
    loadingWrapper.appendChild(loadingDiv);
    chatMessages.appendChild(loadingWrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    const systemInstruction = `You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. Answer in polite Tagalog or Taglish regarding pets and vet care.`;
    const fullPrompt = `${systemInstruction}\n\nTanong ng pet owner: "${promptText}"`;
    let aiResponseText = null;

    for (const modelName of modelCandidates) {
        try {
            console.log(`Sinusubukang tawagin ang model: ${modelName}`);
            const response = await ai.models.generateContent({
                model: modelName,
                contents: fullPrompt
            });
            if (response && response.text && response.text.trim().length > 0) {
                aiResponseText = response.text.trim();
                console.log(`Tagumpay sa model: ${modelName}`);
                break;
            }
        } catch (err) {
            console.error(`Error sa model ${modelName}:`, err);
        }
    }

    if (!aiResponseText) {
        aiResponseText = "Pasensya na, nagka-problema sa koneksyon sa Gemini API. Pakitingnan ang Console (F12) sa iyong browser para sa detalye ng error.";
    }

    const loadElem = document.getElementById(loadingId);
    if (loadElem) loadElem.remove();
    
    appendMessageUI("ai", aiResponseText);
    await saveMessageToFirestore("ai", aiResponseText);
}

window.sendSuggestion = function(text) {
    handleSendMessage(text);
};

document.addEventListener("DOMContentLoaded", () => {
    loadChatHistory();
    
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

    const sendBtn = document.getElementById("sendBtn");
    const userInput = document.getElementById("userInput");
    if (sendBtn) {
        sendBtn.addEventListener("click", (e) => {
            e.preventDefault();
            handleSendMessage();
        });
    }
    if (userInput) {
        userInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                handleSendMessage();
            }
        });
    }
});