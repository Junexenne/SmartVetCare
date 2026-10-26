import { auth, db } from "./firebase-config.js";
import {
  signInWithEmailAndPassword, setPersistence, browserLocalPersistence,
  browserSessionPersistence, signOut, multiFactor, TotpMultiFactorGenerator,
  getMultiFactorResolver, sendEmailVerification
} from "https://www.gstatic.com/firebasejs/12.2.1/firebase-auth.js";
import { collection, query, where, getDocs } from "https://www.gstatic.com/firebasejs/12.2.1/firebase-firestore.js";
const $ = id => document.getElementById(id);
let resolver = null;
let enrollmentSecret = null;
let busy = false;
const modal = document.createElement('div');
modal.id = 'totpModal';
modal.setAttribute('role', 'dialog');
modal.setAttribute('aria-modal', 'true');
modal.setAttribute('aria-label', 'Authenticator verification');
modal.style.cssText = 'display:none;position:fixed;inset:0;z-index:2000;background:rgba(10,25,52,.72);align-items:center;justify-content:center;padding:16px;font-family:Poppins,Arial,sans-serif';
modal.innerHTML = `
 <section style="width:100%;max-width:420px;max-height:90vh;overflow-y:auto;background:white;padding:28px;border-radius:16px;text-align:center;box-shadow:0 12px 40px #0003">
   <div style="font-size:30px;color:#173F81">🔐</div>
   <h2 id="totpHeading" style="color:#173F81;font-size:21px;margin:10px 0">Authenticator verification</h2>
   <p id="totpMessage" style="color:#53627a;font-size:13px;line-height:1.6"></p>
   <div id="totpSetup" hidden>
      <div id="totpQR" style="display:flex;justify-content:center;margin:14px auto"></div>
      <p style="font-size:12px;color:#566">Can't scan? Add this key manually:</p>
      <code id="totpKey" style="display:block;padding:12px;background:#edf3fa;border-radius:8px;font-size:12px;overflow-wrap:anywhere;user-select:all"></code>
   </div>
   <form id="totpForm">
      <label for="totpCode" style="display:block;text-align:left;font-weight:600;font-size:13px;margin:14px 0 5px">6-digit authenticator code</label>
      <input id="totpCode" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" required placeholder="000000" style="width:100%;padding:13px;border-radius:8px;border:1px solid #aebbd0;text-align:center;font-size:22px;letter-spacing:6px;box-sizing:border-box" />
      <button id="totpVerify" type="submit" style="width:100%;background:#173F81;color:#fff;padding:13px;border:0;border-radius:8px;margin-top:14px;font-weight:600;cursor:pointer">Verify & continue</button>
   </form>
   <p id="totpError" role="alert" style="color:#b42318;font-size:12px;min-height:18px"></p>
   <button id="totpCancel" type="button" style="border:0;background:none;color:#57677b;text-decoration:underline;cursor:pointer">Cancel / Back to Login</button>
 </section>`;
document.body.appendChild(modal);

// Strict Numeric restriction for OTP input field
const totpCodeInput = $('totpCode');
if (totpCodeInput) {
  totpCodeInput.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 6);
  });
  totpCodeInput.addEventListener('keypress', (e) => {
    if (e.key < '0' || e.key > '9') {
      e.preventDefault();
    }
  });
  totpCodeInput.addEventListener('paste', (e) => {
    e.preventDefault();
    const pasteData = (e.clipboardData || window.clipboardData).getData('text');
    const numericOnly = pasteData.replace(/[^0-9]/g, '').slice(0, 6);
    document.execCommand ? document.execCommand('insertText', false, numericOnly) : (totpCodeInput.value = numericOnly);
  });
}

function toast(title, message, type='error') {
  if (typeof window.showToast === 'function') window.showToast(title, message, type);
  else alert(`${title}: ${message}`);
}
function display(mode, message) {
  $('totpHeading').textContent = mode === 'enroll' ? 'Set up two-factor authentication' : 'Two-factor authentication';
  $('totpMessage').textContent = message;
  $('totpSetup').hidden = mode !== 'enroll';
  $('totpCode').value = '';
  $('totpError').textContent = '';
  modal.dataset.mode = mode;
  modal.style.display = 'flex';
  $('totpCode').focus();
}
async function accountFromFirestore(user) {
  const q = query(collection(db, 'users'), where('email', '==', user.email));
  const snapshot = await getDocs(q);
  if (snapshot.empty) throw new Error('User record not found.');
  const data = snapshot.docs[0].data();
  if (data.status !== 'active') throw new Error('Account is not active. Please contact the clinic.');
  return { ...data, userDocId: snapshot.docs[0].id };
}
async function finishLogin(user) {
  const data = await accountFromFirestore(user);
  const activeUserId = data.ownerId || data.userDocId;
  const idToken = await user.getIdToken(true);
  const response = await fetch('../auth/session_handler.php', {
    method: 'POST', credentials:'same-origin', headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ idToken, user_id: activeUserId, email: user.email, role: data.role })
  });
  if (!response.ok) {
  const result = await response.json().catch(() => ({}));
  throw new Error(
    result.error || `Session failed (HTTP ${response.status})`
  );
}
  localStorage.setItem('isLoggedIn', 'true');
  localStorage.setItem('userUID', user.uid);
  localStorage.setItem('userEmail', user.email || '');
  localStorage.setItem('ownerId', activeUserId);
  localStorage.setItem('fullName', data.fullName || '');
  localStorage.setItem('phone', data.phone || '');
  localStorage.setItem('address', data.address || '');
  localStorage.setItem('role', data.role || '');
  modal.style.display = 'none';
  window.location.href = '../pages/dashboard.php';
}
async function beginEnrollment(user) {
  if (!user.emailVerified) {
    await sendEmailVerification(user);
    await signOut(auth);
    throw new Error('Verify your email first. We sent you a verification email. After verifying, log in again to set up your authenticator.');
  }
  display('enroll', 'Scan the QR code with Google Authenticator or Microsoft Authenticator, then enter the 6-digit code to finish setup.');
  const session = await multiFactor(user).getSession();
  enrollmentSecret = await TotpMultiFactorGenerator.generateSecret(session);
  $('totpKey').textContent = enrollmentSecret.secretKey;
  $('totpQR').replaceChildren();
  if (window.QRCode) {
    new window.QRCode($('totpQR'), { text: enrollmentSecret.generateQrCodeUrl(user.email || user.uid, 'Smart Vet Care'), width: 180, height: 180 });
  } else {
    $('totpQR').textContent = 'Use the manual setup key below (QR library unavailable).';
  }
}
async function login(event) {
  event.preventDefault();
  if (busy) return;
  busy = true;
  const email = $('email').value.trim(), password = $('password').value;
  try {
    if (!email || !password) throw new Error('Enter your email and password.');
    await setPersistence(auth, $('rememberMe').checked ? browserLocalPersistence : browserSessionPersistence);
    if ($('rememberMe').checked) localStorage.setItem('savedUserEmail', email);
    else localStorage.removeItem('savedUserEmail');
    resolver = null;
    enrollmentSecret = null;
    let credential;
    try {
      credential = await signInWithEmailAndPassword(auth, email, password);
    } catch (err) {
      if (err.code === 'auth/multi-factor-auth-required') {
        resolver = getMultiFactorResolver(auth, err);
        if (!resolver.hints.some(hint => hint.factorId === TotpMultiFactorGenerator.FACTOR_ID)) {
          throw new Error('This account uses another second-factor method, which this page cannot verify.');
        }
        display('challenge', 'Enter the current 6-digit code from your authenticator app.');
        return;
      }
      throw err;
    }
    if (multiFactor(credential.user).enrolledFactors.some(f => f.factorId === TotpMultiFactorGenerator.FACTOR_ID)) {
      await finishLogin(credential.user);
    } else {
      await accountFromFirestore(credential.user);
      await beginEnrollment(credential.user);
    }
  } catch (err) {
    console.error('Sign-in error', err);
    toast('Login unsuccessful', err.code === 'auth/invalid-credential' ? 'Invalid email or password.' : err.message);
    if (auth.currentUser) await signOut(auth);
    modal.style.display = 'none';
  } finally { busy = false; }
}
$('totpForm').addEventListener('submit', async event => {
  event.preventDefault();
  if (busy) return;
  const otp = $('totpCode').value.trim();
  if (!/^\d{6}$/.test(otp)) { $('totpError').textContent = 'Enter exactly 6 digits.'; return; }
  busy = true;
  $('totpVerify').disabled = true;
  $('totpError').textContent = '';
  try {
    if (modal.dataset.mode === 'challenge') {
      if (!resolver) throw new Error('Session expired. Please sign in again.');
      const hint = resolver.hints.find(h => h.factorId === TotpMultiFactorGenerator.FACTOR_ID);
      const assertion = TotpMultiFactorGenerator.assertionForSignIn(hint.uid, otp);
      const result = await resolver.resolveSignIn(assertion);
      await finishLogin(result.user);
    } else {
      if (!auth.currentUser || !enrollmentSecret) throw new Error('Setup expired. Sign in again.');
      const assertion = TotpMultiFactorGenerator.assertionForEnrollment(enrollmentSecret, otp);
      await multiFactor(auth.currentUser).enroll(assertion, 'Authenticator app');
      await signOut(auth);
      resolver = null;
      enrollmentSecret = null;
      modal.style.display = 'none';
      toast('Authenticator enabled', 'Setup complete. Please log in again and enter your authenticator code.', 'success');
    }
  } catch (err) {
    console.error('TOTP error', err);
    $('totpError').textContent = err.message || 'Verification failed. Try the newest code.';
  } finally { busy = false; $('totpVerify').disabled = false; }
});
$('totpCancel').addEventListener('click', async () => {
  if (busy) return;
  resolver = null; enrollmentSecret = null;
  modal.style.display = 'none';
  await signOut(auth);
});
const savedEmail = localStorage.getItem('savedUserEmail');
if (savedEmail && $('email') && $('rememberMe')) { $('email').value = savedEmail; $('rememberMe').checked = true; }
$('loginForm')?.addEventListener('submit', login);