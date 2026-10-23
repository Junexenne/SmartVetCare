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

// 1. PALITAN MO NG BAGONG API KEY MULA SA GOOGLE AI STUDIO KUNG KAILANGAN
const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6Jyk_k7hCoG54XdpZf5WXCbq2a73JUsrxqw5DqmNrF3zQ";
const ai = new GoogleGenAI({ apiKey: apiKey });
const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";

// KEYWORD-BASED PRESET ANSWERS (INSTANT & 0 TOKEN COST)
const presetTopics = [
    {
        keywords: ['vomit', 'vomiting', 'suka', 'nagsusuka', 'puke', 'throwing up', 'puking'],
        answer: `Vomiting in dogs can be caused by eating too fast, sudden diet changes, or underlying medical issues.

<b>Common Symptoms to Watch Out For:</b>
• Lethargy (loss of energy)
• Loss of appetite
• Dehydration (dry gums)
• Vomit containing blood, bile, or foam

<i>Note: If your pet has been vomiting for more than 24 hours, please visit our clinic immediately for an examination.</i>`
    },
    {
        keywords: ['book', 'booking', 'appointment', 'schedule', 'sched', 'reserve', 'reservation'],
        answer: `Booking an appointment online is quick and easy!

<b>Steps to Book:</b>
1. Navigate to the <b>Book Appointment</b> tab in the sidebar menu.
2. Select your pet from the list.
3. Choose your desired service (Check-up, Vaccination, Surgery, Grooming, etc.).
4. Select your preferred date and available time slot.
5. Click <b>Confirm Booking</b>.

You will receive an instant confirmation on your portal dashboard!`
    },
    {
        keywords: ['hour', 'hours', 'time', 'open', 'operating', 'schedule', 'close', 'closing'],
        answer: `Welcome! Here are the official operating hours for <b>Furry Friends Animal Clinic</b>:

<b>Clinic Schedule:</b>
• Monday to Sunday
• 10:00 AM – 7:00 PM

We are open daily to serve you and care for your beloved pets!`
    },
    {
        keywords: ['cat', 'lethargic', 'diet', 'nutrition', 'food', 'cat food', 'pusa', 'ayaw kumain'],
        answer: `When a cat is lethargic or losing appetite:

<b>Nutritional Guidance:</b>
• Offer highly digestible wet food or warm, unseasoned chicken broth (no onions, garlic, or salt).
• Ensure clean, fresh drinking water is always accessible to prevent dehydration.
• Try hand-feeding or using a syringe (without needle) if they refuse to eat from a bowl.

<i>If your cat refuses food for more than 24 hours, please bring them to the clinic promptly for consultation.</i>`
    },
    {
        keywords: ['emergency', 'urgent', 'critical', 'danger', 'bleeding', 'dying', 'seizure'],
        answer: `Please bring your pet for immediate emergency care if they experience any of the following:

<b>Emergency Warning Signs:</b>
• Difficulty breathing or rapid panting
• Severe physical trauma, deep wounds, or active bleeding
• Inability or difficulty to urinate
• Seizures, collapse, or loss of consciousness
• Extreme lethargy or unresponsiveness

Please call us or proceed directly to the nearest veterinary facility!`
    },
    {
        keywords: ['vaccine', 'vaccines', 'vaccination', 'vax', 'shot', 'shots', 'puppy', 'bakuna', 'tuta'],
        answer: `Essential vaccination routine for puppies:

<b>Puppy Vaccination Schedule:</b>
• <b>6-8 Weeks:</b> 5-in-1 Core Vaccine (DHPP)
• <b>10-12 Weeks:</b> 5-in-1 Booster + Kennel Cough
• <b>14-16 Weeks:</b> Anti-Rabies Vaccine + 5-in-1 Final Booster
• <b>Annual:</b> Yearly Booster Shots

Book an appointment today to start your puppy's official health & vaccination record!`
    }
];

// Helper function para sa keyword matching
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

// 1. Load Chat History
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

// 2. Append Message to UI
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

// 3. Save Message to Firestore
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
async function handleSendMessage(messageText) {
    const userInput = document.getElementById("userInput");
    const chatMessages = document.getElementById("chatMessages");
    const promptText = messageText || (userInput ? userInput.value.trim() : "");
    if (!promptText) return;
    
    // Display & Save User Message
    appendMessageUI("user", promptText);
    await saveMessageToFirestore("user", promptText);
    if (userInput) userInput.value = "";
    
    // CHECK KEYWORD MATCH FOR PRESET ANSWERS (INSTANT, NO API COST)
    const matchedAnswer = getPresetAnswer(promptText);
    if (matchedAnswer) {
        appendMessageUI("ai", matchedAnswer);
        await saveMessageToFirestore("ai", matchedAnswer);
        return; 
    }

    // MANUAL QUERY VIA INTERACTIONS API (GEMINI-3.8-FLASH)
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
    
    const systemInstruction = "You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. Answer in polite Tagalog, Taglish, or English regarding pets and vet care.";
    let aiResponseText = null;

    try {
        console.log("Calling Google AI Interactions API with gemini-3.8-flash...");
        const interaction = await ai.interactions.create({
            model: "gemini-3.8-flash",
            input: `${systemInstruction}\n\nUser Question: ${promptText}`
        });

        if (interaction && interaction.output_text) {
            aiResponseText = interaction.output_text.trim();
            console.log("Success generating AI response!");
        }
    } catch (err) {
        console.error("Interactions API Error:", err);
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