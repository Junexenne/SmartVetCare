// Firebase SDK
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-app.js";

import { getAuth } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

import { getFirestore } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

import { getStorage } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-storage.js";

// Firebase Config
const firebaseConfig = {
    apiKey: "AIzaSyBwHmTjg_rT-bU0NL1c71f5qkonf7H7eNM",
    authDomain: "furryfriendsanimalclinic-13da3.firebaseapp.com",
    projectId: "furryfriendsanimalclinic-13da3",
    storageBucket: "furryfriendsanimalclinic-13da3.firebasestorage.app",
    messagingSenderId: "214577366989",
    appId: "1:214577366989:web:60a23440fb34ed74f52684",
    measurementId: "G-PZPEFBSV25"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

export const auth = getAuth(app);

export const db = getFirestore(app);

export const storage = getStorage(app);