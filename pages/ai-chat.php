<?php
// PHP session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Smart Vet Care AI</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            height: -webkit-fill-available;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background-color: #f8faff;
        }
        .dashboard {
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: row;
            box-sizing: border-box;
            overflow: hidden;
            width: 100vw;
        }
        .sidebar {
            width: 270px;
            height: 100%;
            flex-shrink: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1100 !important;
        }
        .main-content {
            flex: 1;
            background: #f8faff;
            height: 100vh;
            height: 100dvh;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            overflow: hidden;
            min-width: 0;
        }
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
            position: relative;
            padding: 10px 18px 12px 18px;
            gap: 8px;
        }
        .chat-ai-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 50%, #38BDF8 100%);
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
            position: relative;
            z-index: 10;
        }
        .chat-ai-header-content h2 {
            font-size: 14.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 2px 0;
            color: #ffffff;
        }
        .chat-ai-header-content p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto; 
            word-break: break-word;
            overflow-wrap: break-word;
            box-sizing: border-box;
            min-height: 0;
            padding: 10px 6px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fdfcff;
            -webkit-overflow-scrolling: touch;
        }
        .message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }
        .message-wrapper.user {
            align-self: flex-end;
            align-items: flex-end;
        }
        .message-wrapper.ai {
            align-self: flex-start;
            align-items: flex-start;
        }
        .message {
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.4;
            max-width: 100%;
            box-sizing: border-box;
            display: inline-block;
        }
        .message-wrapper.user .message {
            background: #2563EB;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }
        .message-wrapper.ai .message {
            background: #f1f5f9;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }
        .chat-suggestions-wrapper {
            flex-shrink: 0;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            gap: 8px;
            padding: 6px 2px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .chat-suggestions-wrapper::-webkit-scrollbar {
            display: none;
        }
        .suggestion-chip {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .suggestion-chip:hover {
            background: #e2e8f0;
            color: #2563EB;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .suggestion-chip i {
            color: #2563EB;
            font-size: 12px;
        }
        .chat-input-area-wrapper {
            flex-shrink: 0; 
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
        .chat-input-area {
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            gap: 12px;
            padding: 4px 0;
        }
        .chat-input-area input {
            flex: 1;
            min-width: 0;
            box-sizing: border-box;
            padding: 10px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 28px;
            font-size: 13px;
            outline: none;
            background: #f8fafc;
            color: #1e293b;
            transition: all 0.3s ease;
        }
        .chat-input-area input:focus {
            border-color: #2563EB;
            background: #ffffff;
        }
        .chat-input-area button {
            background: linear-gradient(135deg, #163B7A 0%, #2563EB 100%);
            color: #ffffff;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }
        .chat-input-area button:hover {
            opacity: 0.9;
        }
        .chat-disclaimer {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 0;
            text-align: center;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block !important;
        }
      @media (max-width: 768px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1100 !important;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
                background: var(--sidebar-bg, #173F81) !important; 
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
            .main-content {
                padding: 0 !important;
                width: 100vw !important;
                height: calc(100vh - 110px) !important;
                height: calc(100dvh - 110px) !important;
            }
            .chat-container {
                margin: 0 !important;
                width: 100% !important;
                border-radius: 0 !important;
                border: none !important;
                height: 100% !important;
                padding: 8px 10px calc(45px + env(safe-area-inset-bottom, 24px)) 10px !important;
                box-sizing: border-box !important;
            }
            .message-wrapper {
                max-width: 100%;
            }
        }
        @keyframes blink {
            0% { opacity: .2; }
            20% { opacity: 1; }
            100% { opacity: .2; }
        }
    </style>
</head>
<body>
<?php include("pages-header.php"); ?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="dashboard">
    <?php include("../includes/sidebar.php"); ?>
    <div class="main-content">
        <?php include("../includes/topbar.php"); ?>
        <div class="chat-container">
            <div class="chat-ai-header">
                <div class="chat-ai-header-content">
                    <h2>
                        <i class="fa-solid fa-sparkles"></i>
                        Smart Vet Care AI
                    </h2>
                    <p><span style="color: #34d399;">●</span> Online | Your trusted AI companion for instant pet care support.</p>
                </div>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message-wrapper ai">
                    <div class="message">
                        <i class="fa-solid fa-sparkles" style="color: #2563EB; margin-right: 6px;"></i> Hello, Smart Vet Fam! How can I help you today with your pets or booking an appointment?
                    </div>
                </div>
            </div>
            <!-- Quick Suggestion Pills -->
            <div class="chat-suggestions-wrapper">
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What are the common symptoms of vomiting in dogs?')">
                    <i class="fa-solid fa-dog"></i> Dog Vomiting Symptoms
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('How do I book a vet appointment online?')">
                    <i class="fa-solid fa-calendar-check"></i> Book an Appointment
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What are your clinic operating hours?')">
                    <i class="fa-solid fa-clock"></i> Clinic Hours & Schedule
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What is the best diet for a lethargic cat?')">
                    <i class="fa-solid fa-cat"></i> Cat Diet & Nutrition
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('When should I bring my pet for emergency care?')">
                    <i class="fa-solid fa-triangle-exclamation"></i> Emergency Signs
                </button>
                <button class="suggestion-chip" type="button" onclick="sendSuggestion('What vaccines does my puppy need?')">
                    <i class="fa-solid fa-syringe"></i> Puppy Vaccination
                </button>
            </div>
            <div class="chat-input-area-wrapper">
                <div class="chat-input-area">
                    <input type="text" id="userInput" placeholder="Ask anything about pet care..." autocomplete="off">
                    <button id="sendBtn" type="button">
                        <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                    </button>
                </div>
                <div class="chat-disclaimer">
                    <i class="fa-solid fa-triangle-exclamation"></i> Disclaimer: AI-generated guidance only. Consult a licensed vet for medical diagnosis.
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Integrated Script para sa AI at Firestore Database -->
<script type="module">
    import { GoogleGenAI } from "https://esm.run/@google/genai";
    import { db } from "../assets/js/firebase-config.js";
    import { 
        doc, 
        getDoc, 
        setDoc, 
        updateDoc, 
        arrayUnion, 
        serverTimestamp 
    } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
    const apiKey = window.GEMINI_API_KEY || "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM";
    const ai = new GoogleGenAI({ apiKey: apiKey });
    const currentOwnerId = localStorage.getItem("ownerId") || "OWN-00004";
    const modelCandidates = [
        'gemini-2.5-flash',
        'gemini-2.0-flash',
        'gemini-1.5-flash-latest',
        'gemini-1.5-pro-latest',
        'gemini-1.5-flash'
    ];
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
    function generateSmartVetResponse(query) {
        const q = query.toLowerCase();
        if ((q.includes('pakain') || q.includes('pagkain') || q.includes('food') || q.includes('diet') || q.includes('eat') || q.includes('kain')) && !q.includes('bawal') && !q.includes('toxic') && !q.includes('walang gana')) {
            if (q.includes('pusa') || q.includes('cat') || q.includes('kuting')) {
                return 'Para sa malusog na pusa, narito ang mga ligtas at masustansyang pagkain:\n\n1. High-Quality Cat Food (Wet & Dry): Pumili ng may mataas na taurine at protein content.\n2. Nilagang Manok o Isda: Lutuin nang walang asin, sibuyas, o bawang.\n3. Kalabasa: Maganda ihalo nang kaunti para sa panunaw.';
            }
            return 'Para sa malusog na aso, narito ang mga ligtas na pagkain:\n\n1. Complete Dog Food (Kibble o Wet Food).\n2. Nilagang Karne (Lean Meats) na walang asin.\n3. Ligtas na Gulay at Prutas: Kalabasa, carrots, sayote, saging, at mansanas.\n4. Kanin (Plain White/Brown Rice).\n\n⚠️ Iwasan: Tsokolate, sibuyas, bawang, ubas, at lutong buto.';
        }
        if (q.includes('bawal') || q.includes('toxic') || q.includes('lason') || q.includes('chocolate')) {
            return '⚠️ Mga Pagkaing BAWAL at Nakalalason sa Aso at Pusa:\n\n• Tsokolate at Kape\n• Sibuyas at Bawang\n• Ubas at Pasas\n• Xylitol\n• Lutong Buto\n\nDalhin agad sa Furry Friends Animal Clinic kung nakakain nito ang alaga!';
        }
        if (q.includes('bakuna') || q.includes('vaccine') || q.includes('rabies') || q.includes('deworm')) {
            return '💉 Recommended Vaccination & Deworming Schedule:\n\n• 2 Weeks Old: Simula ng Deworming\n• 6–8 Weeks Old: 1st Dose ng 5-in-1 Vaccine\n• 9–11 Weeks Old: 2nd Dose ng Booster\n• 12–16 Weeks Old: Final Booster + Anti-Rabies\n• Yearly: Annual Booster shots';
        }
        if (q.includes('suka') || q.includes('tae') || q.includes('vomit') || q.includes('diarrhea') || q.includes('matamlay') || q.includes('sakit')) {
            return '🩺 Clinical Triage Advice:\n\n1. Hydration: Bigyan ng tubig na may pet electrolytes.\n2. Bland Diet: Malambot na lugaw at nilagang manok.\n3. Obserbahan ang dumi at gilagid.\n\n🚨 Magpatingin agad sa clinic kung mahigit 2 beses nang nagsuka o may dugo.';
        }
        return 'Kumusta! Ako ang iyong Smart Vet Care AI Assistant mula sa Furry Friends Animal Clinic. 🐾\n\nPaano ko matutulungan ang iyong alaga ngayon? Maaari kang magtanong tungkol sa kalusugan, pagkain, o mag-book ng appointment.';
    }
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
        loadingDiv.innerHTML = `<span style="display: inline-flex; gap: 4px; align-items: center;">Nag-iisip<span style="animation: blink 1.4s infinite both;">.</span><span style="animation: blink 1.4s infinite both .2s;">.</span><span style="animation: blink 1.4s infinite both .4s;">.</span></span>`;
        
        loadingWrapper.appendChild(loadingDiv);
        chatMessages.appendChild(loadingWrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        const systemInstruction = `You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. Answer in polite Tagalog or Taglish regarding pets and vet care.`;
        const fullPrompt = `${systemInstruction}\n\nTanong ng pet owner: "${promptText}"`;
        let aiResponseText = null;
        for (const modelName of modelCandidates) {
            try {
                const response = await ai.models.generateContent({
                    model: modelName,
                    contents: fullPrompt
                });
                if (response && response.text && response.text.trim().length > 0) {
                    aiResponseText = response.text.trim();
                    break;
                }
            } catch (err) {
                console.warn(`Nabigo ang ${modelName}, sinusubukan ang susunod...`, err);
            }
        }
        if (!aiResponseText) {
            await new Promise(resolve => setTimeout(resolve, 800));
            aiResponseText = generateSmartVetResponse(promptText);
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
</script>
</body>
</html>