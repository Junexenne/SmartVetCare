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

// 1. API KEY CONFIGURATION
const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6Kux8umlwt_88eObeBN6X0SisCo2B2jq6UBQtZ1H77E3Q";
const ai = new GoogleGenAI({ apiKey: apiKey });
const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";

// KEYWORD-BASED PRESET ANSWERS (INSTANT & 0 TOKEN COST)
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
        keywords: ['cat', 'lethargic', 'diet', 'nutrition', 'food', 'cat food', 'pusa', 'ayaw kumain'],
        answer: "When a cat is lethargic or losing appetite:\n<b>Nutritional Guidance:</b>\n• Offer highly digestible wet food or warm, unseasoned chicken broth (no onions, garlic, or salt).\n• Ensure clean, fresh drinking water is always accessible to prevent dehydration.\n• Try hand-feeding or using a syringe (without needle) if they refuse to eat from a bowl.\n<i>If your cat refuses food for more than 24 hours, please bring them to the clinic promptly for consultation.</i>"
    },
    {
        keywords: ['emergency', 'urgent', 'critical', 'danger', 'bleeding', 'dying', 'seizure'],
        answer: "Please bring your pet for immediate emergency care if they experience any of the following:\n<b>Emergency Warning Signs:</b>\n• Difficulty breathing or rapid panting\n• Severe physical trauma, deep wounds, or active bleeding\n• Inability or difficulty to urinate\n• Seizures, collapse, or loss of consciousness\n• Extreme lethargy or unresponsiveness\nPlease call us or proceed directly to the nearest veterinary facility!"
    },
    {
        keywords: ['vaccine', 'vaccines', 'vaccination', 'vax', 'shot', 'shots', 'puppy', 'bakuna', 'tuta'],
        answer: "Essential vaccination routine for puppies:\n<b>Puppy Vaccination Schedule:</b>\n• <b>6-8 Weeks:</b> 5-in-1 Core Vaccine (DHPP)\n• <b>10-12 Weeks:</b> 5-in-1 Booster + Kennel Cough\n• <b>14-16 Weeks:</b> Anti-Rabies Vaccine + 5-in-1 Final Booster\n• <b>Annual:</b> Yearly Booster Shots\nBook an appointment today to start your puppy's official health & vaccination record!"
    }
];

// Helper function for keyword matching[cite: 5]
function getPresetAnswer(userInputText) {
    const cleanInput = userInputText.toLowerCase();
    for (const topic of presetTopics) {
        const matches = topic.keywords.some(keyword => cleanInput.includes(keyword.toLowerCase()));
        if (matches) {
            return topic.answer;
        }
    }
    return null;
}

// 1. Load Chat History[cite: 5]
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

// 2. Append Message to UI[cite: 5]
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

// 3. Save Message to Firestore[cite: 5]
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

// 4. Primary Send Function[cite: 5]
async function handleSendMessage(messageText) {
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");
    const promptText = messageText || (userInput ? userInput.value.trim() : "");
    if (!promptText) return;
    
    // Display & Save User Message
    appendMessageUI("user", promptText);
    await saveMessageToFirestore("user", promptText);
    if (userInput) userInput.value = "";
    
    // CHECK KEYWORD MATCH FOR PRESET ANSWERS (INSTANT, NO API COST)[cite: 5]
    const matchedAnswer = getPresetAnswer(promptText);
    if (matchedAnswer) {
        appendMessageUI("ai", matchedAnswer);
        await saveMessageToFirestore("ai", matchedAnswer);
        return; 
    }

    // LOADING ANIMATION
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
    
    // STRICT SYSTEM INSTRUCTION FOR VETERINARY GUARDRAIL & ENGLISH RESPONSE
    const systemInstructionContent = `You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. 
CRITICAL RULES:
1. You MUST ONLY answer questions strictly related to pets, dogs, cats, animal health, veterinary care, pet nutrition, pet grooming, and Furry Friends Animal Clinic services or scheduling.
2. You must always reply in professional, clear English.
3. If the user asks a question that is NOT related to pets, animals, or veterinary topics (such as relationship advice, math, history, coding, politics, or general chitchat), you must politely decline to answer using this exact template response:
"I am sorry, but I am strictly focused on providing assistance and information regarding pet care, veterinary services, and the health of your beloved pets at Furry Friends Animal Clinic. How may I help you with your pet today?"`;

    let aiResponseText = null;
    try {
        console.log("Calling Google AI SDK with gemini-3.8-flash...");
        
        // Paggamit ng stable generateContent API method
        const response = await ai.models.generateContent({
            model: "gemini-3.8-flash",
            contents: promptText,
            config: {
                systemInstruction: systemInstructionContent
            }
        });

        if (response && response.text) {
            aiResponseText = response.text.trim();
            console.log("Success generating AI response!");
        }
    } catch (err) {
        console.error("Gemini API Error:", err);
    }
    
    if (!aiResponseText) {
        aiResponseText = "Sorry, there was an issue connecting to the Gemini API. Please make sure your API key is active in Google AI Studio.";
    }
    
    const loadElem = document.getElementById(loadingId);
    if (loadElem) loadElem.remove();
    
    appendMessageUI("ai", aiResponseText);
    await saveMessageToFirestore("ai", aiResponseText);
}

// Global Suggestion Function
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