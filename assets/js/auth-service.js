import { auth, db } from "./firebase-config.js";

import {
    signInWithEmailAndPassword,
    createUserWithEmailAndPassword,
    sendPasswordResetEmail,
    signOut
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";

import {
    doc,
    getDoc,
    setDoc,
    serverTimestamp
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";

// ============================
// USER LOGIN
// ============================

export async function login(email, password) {

    return await signInWithEmailAndPassword(auth, email, password);

}

// ============================
// ADMIN LOGIN
// ============================

export async function adminLogin(email, password) {

    const userCredential = await signInWithEmailAndPassword(auth, email, password);

    const uid = userCredential.user.uid;

    const userDoc = await getDoc(doc(db, "users", uid));

    if (!userDoc.exists()) {

        throw new Error("User record not found.");

    }

    const userData = userDoc.data();

    if (userData.role !== "admin") {

        await signOut(auth);

        throw new Error("Access denied. This account is not an administrator.");

    }

    return userCredential;

}

// ============================
// REGISTER
// ============================

export async function register(email, password) {

    return await createUserWithEmailAndPassword(auth, email, password);

}

// ============================
// FORGOT PASSWORD
// ============================

export async function forgotPassword(email) {

    return await sendPasswordResetEmail(auth, email);

}

// ============================
// LOGOUT
// ============================

export async function logout() {

    return await signOut(auth);

}