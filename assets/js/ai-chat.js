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
const ai = new GoogleGenAI({ apiKey: "AQ.Ab8RN6IYIzqHXzYNS2gDXwNaltmz6zHIB6Ix9COk1iA4SR88jQ" });

const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00005"; 

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
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (error) {
        console.error("Error loading chat history:", error);
    }
}

// 2. Append Message to UI
function appendMessageUI(sender, text, scroll = true) {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;

    const msgDiv = document.createElement("div");
    msgDiv.classList.add("message", sender);
    msgDiv.textContent = text;
    chatMessages.appendChild(msgDiv);

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

// 4. Primary Send Function
async function handleSendMessage() {
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");

    if (!userInput) return;
    const promptText = userInput.value.trim();
    if (!promptText) return;

    // A. I-display at i-save ang user message
    appendMessageUI("user", promptText);
    await saveMessageToFirestore("user", promptText);
    userInput.value = "";

    // B. Loading state para kay AI na may animated dots
    const loadingId = "loading-" + Date.now();
    const loadingDiv = document.createElement("div");
    loadingDiv.classList.add("message", "ai");
    loadingDiv.id = loadingId;
    loadingDiv.innerHTML = `<span style="display: inline-flex; gap: 4px; align-items: center;">Nag-iisip<span style="animation: blink 1.4s infinite both;">.</span><span style="animation: blink 1.4s infinite both .2s;">.</span><span style="animation: blink 1.4s infinite both .4s;">.</span></span>`;
    chatMessages.appendChild(loadingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
        const systemInstruction = `
            You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. 
            CRITICAL RULE 1: You must ONLY answer questions related to pets, animals, veterinary care, health monitoring, pet nutrition, and clinic services. If the user asks about anything unrelated, politely decline.
            CRITICAL RULE 2: Always match the language of the user. If the user asks in Tagalog or Taglish, you MUST reply in polite, clear, and helpful Tagalog or Taglish. 
            Keep your answers professional and structured.
        `;

        const response = await ai.models.generateContent({
            model: 'gemini-2.0-flash',
            contents: [
                {
                    role: 'user',
                    parts: [{ text: `${systemInstruction}\n\nUser Question: ${promptText}` }]
                }
            ]
        });

        const aiResponseText = response.text;

        // C. Alisin ang loading, i-display at i-save ang sagot ni AI
        const loadElem = document.getElementById(loadingId);
        if (loadElem) loadElem.remove();

        appendMessageUI("ai", aiResponseText);
        await saveMessageToFirestore("ai", aiResponseText);

    } catch (error) {
        console.error("Gemini Error:", error);
        const loadElement = document.getElementById(loadingId);
        if (loadElement) {
            loadElement.textContent = "Paumanhin, nagkaroon ng error sa pagkonekta sa AI assistant. Siguraduhing tama ang iyong API key.";
        }
    }
}

// 5. I-attach ang listeners pagka-load ng DOM
document.addEventListener("DOMContentLoaded", () => {
    loadChatHistory();

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