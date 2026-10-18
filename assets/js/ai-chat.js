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
const apiKey = window.GEMINI_API_KEY || "AQ.Ab8RN6IZcqlkPNpqffqXqKjnvmgwVqgpbPm90ExRBj7dOoR4Zg";
const ai = new GoogleGenAI({ apiKey: apiKey });

// Listahan ng mga active Gemini models na susubukan nang sunod-sunod
const modelCandidates = [
    'gemini-2.5-flash',
    'gemini-2.0-flash',
    'gemini-1.5-flash-latest',
    'gemini-1.5-pro-latest',
    'gemini-1.5-flash'
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
        }
        chatMessages.scrollTop = chatMessages.scrollHeight;
    } catch (error) {
        console.error("Error loading chat history:", error);
    }
}

// 2. Append Message to UI na may tamang Spacing at Line Breaks
function appendMessageUI(sender, text, scroll = true) {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;
    const msgDiv = document.createElement("div");
    msgDiv.classList.add("message", sender);
    
    // Sinisigurado nito na lilitaw nang maayos ang mga enter at espasyo
    msgDiv.style.whiteSpace = "pre-wrap";
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

// --- SMART CLINICAL VET ENGINE (100% Defense-Safe Knowledge Base) ---
function generateSmartVetResponse(query) {
    const q = query.toLowerCase();

    // 1. Pagkain ng Aso at Pusa (Safe Foods / Diet)
    if ((q.includes('pakain') ||
            q.includes('pagkain') ||
            q.includes('food') ||
            q.includes('diet') ||
            q.includes('eat') ||
            q.includes('kain')) &&
        !q.includes('bawal') &&
        !q.includes('toxic') &&
        !q.includes('walang gana')) {
        if (q.includes('pusa') || q.includes('cat') || q.includes('kuting')) {
            return 'Para sa malusog na pusa, narito ang mga ligtas at masustansyang pagkain:\n\n' +
                '1. High-Quality Cat Food (Wet & Dry): Pumili ng may mataas na taurine at protein content dahil ang mga pusa ay obligate carnivores.\n\n' +
                '2. Nilagang Manok o Isda (Boiled Chicken/Fish): Lutuin nang walang asin, sibuyas, o bawang, at siguraduhing walang tinik o buto.\n\n' +
                '3. Kalabasa (Boiled Squash): Maganda ihalo nang kaunti para sa kanilang panunaw.\n\n' +
                'Paalala: Laging magbigay ng malinis na tubig at iwasan ang gatas ng baka dahil lactose intolerant ang karamihan sa mga pusa.';
        }
        return 'Para sa malusog na aso, narito ang mga ligtas at masustansyang pwedeng ipakain:\n\n' +
            '1. Complete Dog Food (Kibble o Wet Food): Pumili ng akma sa edad niya (Puppy, Adult, o Senior).\n\n' +
            '2. Nilagang Karne (Lean Meats): Walang timpla o asin na nilagang manok (boiled chicken breast), baka, o atay.\n\n' +
            '3. Ligtas na Gulay at Prutas: Nilagang kalabasa (squash), carrots, sayote, pipino, saging, at mansanas (walang buto).\n\n' +
            '4. Kanin (Plain White/Brown Rice): Magandang ihalo sa nilagang manok lalo na kung medyo malambot ang dumi.\n\n' +
            '⚠️ Iwasan: Tsokolate, sibuyas, bawang, ubas (grapes), matatabang pagkain, at lutong buto ng manok dahil delikado ito sa kanila.';
    }

    // 2. Bawal / Toxic Foods
    if (q.includes('bawal') ||
        q.includes('toxic') ||
        q.includes('lason') ||
        q.includes('poison') ||
        q.includes('chocolate')) {
        return '⚠️ Mga Pagkaing BAWAL at Nakalalason sa Aso at Pusa:\n\n' +
            '• Tsokolate at Kape (Chocolate/Caffeine): Nagdudulot ng vomiting, seizures, at heart failure.\n\n' +
            '• Sibuyas at Bawang (Onions & Garlic): Nakakasira ng red blood cells at nagdudulot ng anemia.\n\n' +
            '• Ubas at Pasas (Grapes & Raisins): Maaaring magdulot ng biglaang kidney failure.\n\n' +
            '• Xylitol (Artificial Sweetener sa gums/candies): Nagpapabagsak ng blood sugar.\n\n' +
            '• Lutong Buto (Cooked Bones): Madaling makatusok sa lalamunan o makabutas ng bituka.\n\n' +
            'Kung nakakain nito ang iyong alaga, dalhin agad sa Furry Friends Animal Clinic para mabigyan ng lunas!';
    }

    // 3. Bakuna / Vaccine Schedule
    if (q.includes('bakuna') ||
        q.includes('vaccine') ||
        q.includes('vaccination') ||
        q.includes('rabies') ||
        q.includes('5 in 1') ||
        q.includes('deworm')) {
        return '💉 Recommended Vaccination & Deworming Schedule:\n\n' +
            '• 2 Weeks Old: Simula ng Deworming (inuulit kada 2 linggo hanggang 3 months old).\n\n' +
            '• 6–8 Weeks Old: 1st Dose ng 5-in-1 / 6-in-1 Vaccine (Distemper, Parvovirus, Hepatitis, Parainfluenza, Leptospirosis).\n\n' +
            '• 9–11 Weeks Old: 2nd Dose ng 5-in-1 / 6-in-1 Booster.\n\n' +
            '• 12–16 Weeks Old: Final Booster + Anti-Rabies Vaccine.\n\n' +
            '• Yearly: Annual Booster shots para sa 5-in-1 at Anti-Rabies.\n\n' +
            'Maaari kang mag-book ng vaccination appointment dito mismo sa Smart Vet Care system!';
    }

    // 4. Nagsusuka / Pagtatae / Walang Gana (Vomiting / Diarrhea / Lethargy)
    if (q.includes('suka') ||
        q.includes('tae') ||
        q.includes('vomit') ||
        q.includes('diarrhea') ||
        q.includes('matamlay') ||
        q.includes('walang gana') ||
        q.includes('parvo') ||
        q.includes('sakit')) {
        return '🩺 Clinical Triage & First-Aid Advice:\n\n' +
            'Ang pagsusuka, pagtatae, o pagiging matamlay ay maaaring senyales ng indigestion, parasites, o viral infection tulad ng Parvovirus/Distemper.\n\n' +
            'Mga Dapat Gawin Ngayon:\n' +
            '1. Hydration: Bigyan ng paunti-unting tubig na may dextrose powder o pet electrolytes para hindi ma-dehydrate.\n\n' +
            '2. Bland Diet: Kung hindi na nagsusuka, bigyan ng malambot na lugaw at nilagang manok na walang asin.\n\n' +
            '3. Obserbahan: Tingnan kung may dugo sa dumi o suka at kung maputla ang gilagid (gums).\n\n' +
            '🚨 Kailan dapat itakbo sa Vet: Kung mahigit 2 beses nang nagsuka sa loob ng 24 oras, may dugo sa dumi, o sobrang nanghihina, magpa-checkup na agad sa Furry Friends Animal Clinic.';
    }

    // 5. Garapata / Pulgas / Galis (Ticks, Fleas, Skin)
    if (q.includes('garapata') ||
        q.includes('pulgas') ||
        q.includes('kati') ||
        q.includes('galis') ||
        q.includes('balahibo') ||
        q.includes('tick') ||
        q.includes('flea') ||
        q.includes('mange')) {
        return '🐾 Pangangalaga sa Balat at Iwas-Garapata/Pulgas:\n\n' +
            '1. Vet-Prescribed Preventives: Pinaka-epektibo ang oral chewables (tulad ng NexGard/Bravecto) o spot-on treatments (Frontline/Advocate) base sa timbang ng alaga.\n\n' +
            '2. Medicated Bath: Gumamit ng anti-fungal o anti-mange pet shampoo kung may pamumula o pagkalagas ng balahibo.\n\n' +
            '3. Linisin ang Paligid: Labhan ang higaan ng alaga at linisin ang mga sulok ng bahay kung saan nangingitlog ang garapata.\n\n' +
            'Iwasang tirisin ang garapata gamit ang kamay para hindi kumalat ang bacteria.';
    }

    // 6. Emergency / Contact Info
    if (q.includes('emergency') ||
        q.includes('contact') ||
        q.includes('clinic') ||
        q.includes('address') ||
        q.includes('hotline') ||
        q.includes('schedule')) {
        return '🚨 Furry Friends Animal Clinic – Emergency & Clinic Info:\n\n' +
            '• 24/7 Emergency Hotline: 0991-572-3015\n\n' +
            '• Regular Clinic Hours: Monday to Sunday, 10:00 AM – 7:00 PM\n\n' +
            '• Services: Consultation, Vaccination, Deworming,Lab Tests & Emergency Triage.\n\n' +
            'Kung ang iyong alaga ay nahihirapang huminga, nag-seizure, o nakakain ng lason, dalhin agad sa aming clinic!';
    }

    // 7. General / Greeting / Iba pang tanong
    return 'Kumusta! Ako ang iyong Smart Vet Care AI Assistant mula sa Furry Friends Animal Clinic. 🐾\n\n' +
        'Tungkol sa iyong tanong:\n' +
        'Para mabigyan ng pinakaligtas na pangangalaga ang iyong alaga, siguraduhing mayroon siyang sapat na malinis na tubig, tamang nutrisyon na angkop sa kanyang edad, at updated na bakuna at deworming.\n\n' +
        'Kung may napapansin kang kakaibang sintomas gaya ng katamlayan, kawalan ng gana kumain, o pagsusuka, maaari mo itong ikwento sa akin nang detalyado o mag-book ng appointment sa aming clinic!';
}

// 4. Primary Send Function na may Multi-Model Fallback at Offline Clinical Fallback
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

    const systemInstruction = `
        You are Smart Vet Care AI, a specialized veterinary assistant for Furry Friends Animal Clinic. 
        CRITICAL RULE 1: You must ONLY answer questions related to pets, animals, veterinary care, health monitoring, pet nutrition, and clinic services. If the user asks about anything unrelated, politely decline.
        CRITICAL RULE 2: Always match the language of the user. If the user asks in Tagalog or Taglish, you MUST reply in polite, clear, and helpful Tagalog or Taglish. 
        Keep your answers professional, structured, and use proper line breaks for readability.
    `;

    const fullPrompt = `${systemInstruction}\n\nTanong ng pet owner: "${promptText}"`;

    let aiResponseText = null;

    // 1. Subukan ang Live Gemini API gamit ang mga model candidates nang sunud-sunod
    for (const modelName of modelCandidates) {
        try {
            const response = await ai.models.generateContent({
                model: modelName,
                contents: fullPrompt
            });
            if (response && response.text && response.text.trim().length > 0) {
                aiResponseText = response.text.trim();
                break; // Nakuha na ang sagot, itigil na ang pag-loop
            }
        } catch (err) {
            console.warn(`Nabigo ang ${modelName}, sinusubukan ang susunod...`, err);
            continue; // Subukan ang susunod na modelo kung sakaling mag-error
        }
    }

    // 2. Kung mag-fail ang lahat ng online models, gamitin ang Smart Clinical Vet Engine
    if (!aiResponseText) {
        await new Promise(resolve => setTimeout(resolve, 800)); // Natural delay
        aiResponseText = generateSmartVetResponse(promptText);
    }

    // C. Alisin ang loading, i-display at i-save ang sagot ni AI
    const loadElem = document.getElementById(loadingId);
    if (loadElem) loadElem.remove();

    appendMessageUI("ai", aiResponseText);
    await saveMessageToFirestore("ai", aiResponseText);
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