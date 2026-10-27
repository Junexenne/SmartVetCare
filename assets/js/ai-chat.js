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

const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6Kux8umlwt_88eObeBN6X0SisCo2B2jq6UBQtZ1H77E3Q";
const ai = new GoogleGenAI({ apiKey: apiKey });
const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";

const presetTopics = [
    {
        keywords: ['vomit', 'vomiting', 'suka', 'nagsusuka', 'puke', 'throwing up', 'puking'],
        answer: "Vomiting in dogs can be caused by eating too fast, sudden diet changes, or underlying medical issues.\n<b>Common Symptoms to Watch Out For:</b>\n• Lethargy (loss of energy)\n• Loss of appetite\n• Dehydration (dry gums)\n• Vomit containing blood, bile, or foam\n<i>Note: If your pet has been vomiting for more than 24 hours, please visit our clinic immediately for an examination.</i>"
    },
    {
        keywords: ['book', 'booking', 'appointment', 'schedule', 'sched', 'reserve', 'reservation'],
        answer: "Booking an appointment online is quick and easy!\n<b>Steps to Book:</b>\n1. Navigate to the <b>Book Appointment</b> tab in the sidebar menu.\n2. Select your pet from the list.\n3. Choose your desired service (Check-up, Vaccination, Surgery, Grooming, etc.).\n4. Select your preferred date and available time slot.\n5. Click <b>Confirm Booking</b>.\nYou will receive an instant confirmation on your portal dashboard!"
    },
    {
        keywords: ['hour', 'hours', 'time', 'open', 'operating', 'schedule', 'close', 'closing'],
        answer: "Welcome! Here are the official operating hours for <b>Furry Friends Animal Clinic</b>:\n<b>Clinic Schedule:</b>\n• Monday to Sunday\n• 10:00 AM – 7:00 PM\nWe are open daily to serve you and care for your beloved pets!"
    },
    {
        keywords: ['emergency', 'urgent', 'critical', 'danger', 'bleeding', 'dying', 'seizure'],
        answer: "Please bring your pet for immediate emergency care if they experience any of the following:\n<b>Emergency Warning Signs:</b>\n• Difficulty breathing or rapid panting\n• Severe physical trauma, deep wounds, or active bleeding\n• Inability or difficulty to urinate\n• Seizures, collapse, or loss of consciousness\n• Extreme lethargy or unresponsiveness\nPlease call us or proceed directly to the nearest veterinary facility!"
    }
];

function getPresetAnswer(userInputText) {
    const cleanInput = userInputText.toLowerCase();
    for (const topic of presetTopics) {
        const matches = topic.keywords.some(keyword => cleanInput.includes(keyword.toLowerCase()));
        if (matches) return topic.answer;
    }
    return null;
}

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
            appendMessageUI('ai', '<i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 6px;"></i> KuaGO is ready to help! Ask about your pets, clinic services, or appointments at Furry Friends Animal Clinic. 🐾', false);
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (error) {
        console.error("Error loading chat history:", error);
    }
}

function appendMessageUI(sender, text, scroll = true) {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;
    const wrapperDiv = document.createElement("div");
    wrapperDiv.classList.add("message-wrapper", sender === 'ai' ? 'ai' : 'user');
    
    const msgDiv = document.createElement("div");
    msgDiv.classList.add("message");
    msgDiv.style.whiteSpace = "pre-wrap";
    
    if (sender === 'ai' && text.includes('KuaGO is ready')) {
        msgDiv.innerHTML = text;
    } else if (sender === 'ai') {
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

async function handleSendMessage(messageText) {
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");
    const promptText = messageText || (userInput ? userInput.value.trim() : "");
    if (!promptText) return;
    
    appendMessageUI("user", promptText);
    await saveMessageToFirestore("user", promptText);
    if (userInput) userInput.value = "";
    
    const matchedAnswer = getPresetAnswer(promptText);
    if (matchedAnswer) {
        appendMessageUI("ai", matchedAnswer);
        await saveMessageToFirestore("ai", matchedAnswer);
        return; 
    }

    const loadingId = "loading-" + Date.now();
    const loadingWrapper = document.createElement("div");
    loadingWrapper.classList.add("message-wrapper", "ai");
    loadingWrapper.id = loadingId;
    
    const loadingDiv = document.createElement("div");
    loadingDiv.classList.add("message");
    loadingDiv.innerHTML = `<span style="display: inline-flex; gap: 4px; align-items: center;"><i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 4px;"></i> KuaGO is analyzing...</span>`;
    
    loadingWrapper.appendChild(loadingDiv);
    chatMessages.appendChild(loadingWrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    const systemInstructionContent = `You are KuaGO, the official AI pet support companion in Smart Vet Care for Furry Friends Animal Clinic. Reply helpfully and concisely to pet owners in Tagalog, English, or Taglish regarding pet health and clinic services.`;
    let aiResponseText = null;
    
    try {
        const response = await ai.models.generateContent({
            model: "gemini-1.5-flash",
            contents: promptText,
            config: {
                systemInstruction: systemInstructionContent
            }
        });
        if (response && response.text) {
            aiResponseText = response.text.trim();
        }
    } catch (err) {
        console.error("Gemini API Error:", err);
    }
    
    if (!aiResponseText) {
        aiResponseText = "KuaGO is temporarily unavailable. Please check your connection and try again later.";
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