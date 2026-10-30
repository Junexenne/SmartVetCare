import { db } from "./firebase-config.js";
import { 
    doc, 
    getDoc, 
    setDoc, 
    updateDoc, 
    arrayUnion, 
    serverTimestamp 
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";

// GROQ API KEY
const GROQ_API_KEY = "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";

const presetTopics = [
    {
        keywords: ['vaccine', 'vaccines', 'vaccination', 'bakuna', 'puppy vaccine', 'dog vaccine'],
        answer: "Puppies typically need core vaccinations starting around 6 to 8 weeks of age, including the 5-in-1 (DHPP) vaccine and Anti-Rabies vaccination to protect them from dangerous infections.\n<b>Core Vaccinations Include:</b>\n• 5-in-1 Vaccine (Distemper, Hepatitis, Parainfluenza, Parvovirus)\n• Anti-Rabies Vaccine\n• Kennel Cough Vaccine (Bordatella)\n<i>Note: Please schedule a visit at Furry Friends Animal Clinic to get an accurate vaccination timeline and schedule for your puppy!</i>"
    },
    
    {
        keywords: ['vomit', 'vomiting', 'suka', 'nagsusuka', 'puke', 'throwing up', 'puking'],
        answer: "Vomiting in dogs can be caused by eating too fast, sudden diet changes, or underlying medical issues.\n<b>Common Symptoms to Watch Out For:</b>\n• Lethargy (loss of energy)\n• Loss of appetite\n• Dehydration (dry gums)\n• Vomit containing blood, bile, or foam\n<i>Note: If your pet has been vomiting for more than 24 hours, please visit our clinic immediately for an examination.</i>"
    },
    {
        keywords: ['book', 'booking', 'appointment', 'schedule', 'sched', 'reserve', 'reservation'],
        answer: "Booking an appointment online is quick and easy!\n<b>Steps to Book:</b>\n1. Navigate to the <b>Book Appointment</b> tab in the sidebar menu.\n2. Select your pet from the list.\n3. Choose your desired service (Check-up, Vaccination, Surgery, Grooming, etc.).\n4. Select your preferred date and available time slot.\n5. Click <b>Confirm Booking</b>.\nYou will receive an instant confirmation on your portal dashboard!"
    },
    {
        keywords: ['hour', 'hours', 'time', 'open', 'operating', 'schedule', 'close', 'closing', 'oras'],
        answer: "Welcome! Here are the official operating hours for <b>Furry Friends Animal Clinic</b>:\n<b>Clinic Schedule:</b>\n• Monday to Sunday\n• 10:00 AM – 7:00 PM\nWe are open daily to serve you and care for your beloved pets!"
    },
    {
        keywords: ['emergency', 'urgent', 'critical', 'danger', 'bleeding', 'dying', 'seizure'],
        answer: "Please bring your pet for immediate emergency care if they experience any of the following:\n<b>Emergency Warning Signs:</b>\n• Difficulty breathing or rapid panting\n• Severe physical trauma, deep wounds, or active bleeding\n• Inability or difficulty to urinate\n• Seizures, collapse, or loss of consciousness\n• Extreme lethargy or unresponsiveness\nPlease call us 0991-572-3015 or proceed directly to the nearest veterinary facility!"
    }
];

function getPresetAnswer(userInputText) {
    const cleanInput = userInputText.toLowerCase();
    for (const topic of presetTopics) {
        const matches = topic.keywords.some(keyword => cleanInput.includes(keyword.toLowerCase()));
        if (matches) return topic.answer + "\n\n<small style='color: #6b7280;'><i>AI-generated guidance only. Consult a licensed vet for medical diagnosis.</i></small>";
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
    
    // Check preset topics first
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
    
    // Improved System Instruction with balanced contextual guardrails & mandatory English output
    const systemInstructionContent = `You are KuaGO, the official AI pet support companion in Smart Vet Care for Furry Friends Animal Clinic.

CRITICAL INSTRUCTIONS:
1. ALWAYS RESPOND IN ENGLISH ONLY: No matter what language the user inputs (Tagalog, Taglish, or English), your final answer MUST ALWAYS be written in clear English.
2. SCOPE & TOPIC EVALUATION:
   - ANSWER ALL PET-RELATED QUESTIONS: Answer queries about dogs, cats, small pets, pet nutrition, pet symptoms, pet vaccines, veterinary health, or Furry Friends Animal Clinic services.
   - REJECT NON-PET TOPICS: If and ONLY IF the user asks about human medical conditions (e.g. human stomach ache, human illness), personal human relationships (e.g. breakups, dating advice), programming code, general politics, or non-animal topics, politely decline by replying ONLY with:
     "I am KuaGO, an AI assistant dedicated ONLY to pet care and services at Furry Friends Animal Clinic. I cannot assist with human medical advice, personal concerns, or non-pet topics."
3. ACCURATE CLINIC DETAILS:
   - Clinic Name: Furry Friends Animal Clinic
   - Hours: Monday to Sunday, 10:00 AM – 7:00 PM daily.
   - Never invent fake telephone numbers or physical addresses. Direct bookings to the "Book Appointment" tab on the portal.
4. TONE: Helpful, concise, empathetic, and professional in English.`;

    let rawAiResponse = null;
    
    try {
        const response = await fetch("https://api.groq.com/openai/v1/chat/completions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${GROQ_API_KEY}`
            },
            body: JSON.stringify({
                model: "openai/gpt-oss-20b",
                messages: [
                    { role: "system", content: systemInstructionContent },
                    { role: "user", content: promptText }
                ],
                temperature: 0.2
            })
        });

        const data = await response.json();
        if (data && data.choices && data.choices[0]?.message?.content) {
            rawAiResponse = data.choices[0].message.content.trim();
        } else if (data.error) {
            console.error("Groq API Error:", data.error.message);
        }
    } catch (err) {
        console.error("Fetch Error:", err);
    }
    
    if (!rawAiResponse) {
        const lowerPrompt = promptText.toLowerCase();
        if (lowerPrompt.includes("bakuna") || lowerPrompt.includes("vaccine")) {
            rawAiResponse = "We offer core pet vaccinations including 5-in-1, Anti-Rabies, and Kennel Cough at Furry Friends Animal Clinic. You can book an appointment on the Book Appointment page.";
        } else if (lowerPrompt.includes("sakit") || lowerPrompt.includes("vet")) {
            rawAiResponse = "Please bring your pet to Furry Friends Animal Clinic (Open daily from 10:00 AM to 7:00 PM) for proper check-up and veterinary diagnosis.";
        } else {
            rawAiResponse = "Hello! I am KuaGO, your AI pet support assistant at Furry Friends Animal Clinic. How can I help you and your pet today?";
        }
    }

    const aiResponseText = rawAiResponse + "\n\n<small style='color: #6b7280;'><i>AI-generated guidance only. Consult a licensed vet for medical diagnosis.</i></small>";
    
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