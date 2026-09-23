<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Feedback - Smart Vet Care</title>
    
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1000;
        }

        .help-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Modern Floating Toast Notification Style */
        .toast-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            background: #ffffff;
            color: #1e293b;
            padding: 16px 24px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 14px;
            z-index: 9999;
            border-left: 5px solid #10b981;
            transform: translateY(-120px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.35);
        }

        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast-icon {
            background: #ecfdf5;
            color: #10b981;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .toast-content h4 {
            margin: 0 0 2px 0;
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .toast-content p {
            margin: 0;
            font-size: 12.5px;
            color: #64748b;
        }

        /* Section Header Titles */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Modern Accordion Help List Style */
        .help-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 40px;
        }

        .help-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .help-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 10px 25px rgba(81, 66, 245, 0.08);
        }

        .help-item {
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            cursor: pointer;
            color: #1e293b;
            user-select: none;
        }

        .help-item-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .help-item i.fa-solid:not(.fa-chevron-down) {
            font-size: 16px;
            color: #5142f5;
            background: #eef4ff;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
            transition: background 0.2s, color 0.2s;
        }

        .help-card:hover .help-item i.fa-solid:not(.fa-chevron-down) {
            background: #5142f5;
            color: #ffffff;
        }

        .help-item span {
            font-size: 15px;
            font-weight: 500;
        }

        .chevron-icon {
            color: #94a3b8;
            transition: transform 0.3s ease, color 0.2s;
            font-size: 13px;
        }

        /* Collapsible Answer Content */
        .help-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            background-color: #f8fafc;
            padding: 0 22px;
            border-top: 1px solid transparent;
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
        }

        .help-card.active .help-answer {
            max-height: 250px;
            padding: 18px 22px;
            border-top-color: #f1f5f9;
        }

        .help-card.active .chevron-icon {
            transform: rotate(180deg);
            color: #5142f5;
        }

        .help-card.active {
            border-color: #5142f5;
            box-shadow: 0 8px 20px rgba(81, 66, 245, 0.08);
        }

        /* Redesigned Feedback Box Card */
        .feedback-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #edf2f7;
            margin-bottom: 40px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feedback-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .feedback-icon-box {
            background: #eef4ff;
            color: #5142f5;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .feedback-card h3 {
            font-size: 18px;
            margin: 0 0 4px 0;
            color: #1e1b4b;
            font-weight: 600;
        }

        .feedback-card p {
            font-size: 13.5px;
            color: #64748b;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #5142f5;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(81, 66, 245, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 130px;
        }

        .btn-submit {
            background-color: #5142f5;
            color: white;
            border: none;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(81, 66, 245, 0.2);
        }

        .btn-submit:hover {
            background-color: #4333e6;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(81, 66, 245, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed !important;
                top: 0;
                left: -270px;
                height: 100% !important;
                transition: left 0.3s ease;
                z-index: 1001;
                box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active {
                left: 0 !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
            .feedback-card {
                padding: 20px;
            }
            .toast-notification {
                left: 20px;
                right: 20px;
                top: 20px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div id="toastNotification" class="toast-notification">
    <div class="toast-icon">
        <i class="fa-solid fa-check"></i>
    </div>
    <div class="toast-content">
        <h4>Thank you!</h4>
        <p>Your feedback has been successfully sent.</p>
    </div>
</div>

<div class="dashboard">

    <?php include("../includes/sidebar.php"); ?>

    <div class="main-content" style="background: #f4f7fe; min-height: 100vh; padding: 20px;">

        <?php include("../includes/topbar.php"); ?>

        <div class="help-container">
            
            <div class="doctors-header" style="margin-bottom: 25px;">
                <h1 style="display: flex; align-items: center; gap: 10px; color: #1e1b4b; font-size: 24px; margin-bottom: 6px; font-weight: 700;">
                    <i class="fa-solid fa-circle-question" style="background: #eef4ff; padding: 10px; border-radius: 12px; color: #5142f5;"></i>
                    Help & Support
                </h1>
                <p style="color: #64748b; font-size: 13px; margin: 0;">
                    Learn how to use the system or send us your feedback and suggestions.
                </p>
            </div>

            <!-- Popular Help Resources with Accordion Answers -->
            <div class="section-title">Popular Help Resources</div>
            <div class="help-list">
                
                <!-- Question 1 -->
                <div class="help-card">
                    <div class="help-item">
                        <div class="help-item-left">
                            <i class="fa-solid fa-key"></i>
                            <span>How to change or reset your account password?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="help-answer">
                        Go to your Account Settings or Profile page, look for the "Change Password" section, enter your current password followed by your new password, and click Save Changes. If you forgot your password, use the "Forgot Password" link on the login page.
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="help-card">
                    <div class="help-item">
                        <div class="help-item-left">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Guide on booking and cancelling clinic appointments</span>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="help-answer">
                        To book an appointment, navigate to the Appointments page, select your preferred date, time, and veterinarian, then confirm. To cancel, go to your upcoming appointments list and click the "Cancel Appointment" button before the scheduled time.
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="help-card">
                    <div class="help-item">
                        <div class="help-item-left">
                            <i class="fa-solid fa-shield-dog"></i>
                            <span>How to update health records and your pet's information?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="help-answer">
                        Access the "My Pets" or "Pet Profiles" section from the sidebar. Choose the specific pet whose details you want to update, click the edit button, modify the information or health logs, and click save to update your database records.
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="help-card">
                    <div class="help-item">
                        <div class="help-item-left">
                            <i class="fa-solid fa-robot"></i>
                            <span>How to use the AI Assistant for veterinary consultations</span>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron-icon"></i>
                    </div>
                    <div class="help-answer">
                        Click on the AI Assistant icon in the sidebar menu. Type your pet's symptoms or general health questions into the chat box, and the system's smart assistant will provide preliminary guidance and care recommendations instantly.
                    </div>
                </div>

            </div>

            <!-- Send Feedback Section -->
            <div class="feedback-card" id="send-feedback-section">
                <div class="feedback-header">
                    <div class="feedback-icon-box">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div>
                        <h3>Send Feedback</h3>
                        <p>Did you notice any issues or do you have suggestions to improve the Smart Vet Care System? Share your thoughts with us.</p>
                    </div>
                </div>
                
                <form id="feedbackForm">
                    <div class="form-group">
                        <label for="feedback_type">Feedback Type</label>
                        <select name="feedback_type" id="feedback_type" class="form-control">
                            <option value="Suggestion">Suggestion / Improvement</option>
                            <option value="Bug">Bug / Technical Error</option>
                            <option value="General">General Feedback</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea name="message" id="message" class="form-control" placeholder="Type your comments, suggestions, or issues here..." required></textarea>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Send Feedback
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Firebase SDK Imports & App Initialization -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
    import { getFirestore, collection, addDoc } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js";

    const firebaseConfig = {
        projectId: "furryfriendsanimalclinic-13da3"
    };

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    function showToast() {
        const toast = document.getElementById('toastNotification');
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }

    document.addEventListener("DOMContentLoaded", () => {
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

        const helpItems = document.querySelectorAll('.help-item');
        helpItems.forEach(item => {
            item.addEventListener('click', () => {
                const parentCard = item.parentElement;
                
                document.querySelectorAll('.help-card').forEach(card => {
                    if (card !== parentCard) {
                        card.classList.remove('active');
                    }
                });

                parentCard.classList.toggle('active');
            });
        });
    });

    document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const feedbackType = document.getElementById('feedback_type').value;
        const message = document.getElementById('message').value;
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

        try {
            await addDoc(collection(db, "feedback"), {
                feedbackType: feedbackType,
                message: message,
                createdAt: new Date()
            });

            showToast();
            document.getElementById('feedbackForm').reset();

        } catch (error) {
            console.error("Error adding feedback: ", error);
            alert("Failed to send feedback. Please try again.");
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Feedback';
        }
    });
</script>

</body>
</html>