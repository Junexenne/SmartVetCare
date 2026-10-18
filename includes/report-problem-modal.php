<!-- REPORT A PROBLEM MODAL (SMART VET CARE UI THEME) -->
<div id="reportProblemModal" class="report-modal-overlay" style="display: none;">
    <div class="report-modal-card">
        <button class="report-modal-close" id="closeReportModalBtn" type="button">&times;</button>
        
        <div class="report-header">
            <div class="report-icon-box">
                <i class="fa-solid fa-flag"></i>
            </div>
            <div>
                <h2>Report a problem</h2>
                <p>Something broken, confusing or missing? Tell us and we'll look into it.</p>
            </div>
        </div>

        <form id="reportProblemForm">
            <!-- What is it about? -->
            <div class="report-section">
                <label class="report-label">What is it about?</label>
                <div class="report-chips-grid">
                    <button type="button" class="report-chip active" data-category="Something isn't working">
                        <i class="fa-solid fa-bug"></i> Something isn't working
                    </button>
                    <button type="button" class="report-chip" data-category="Wrong or missing info">
                        <i class="fa-solid fa-circle-info"></i> Wrong or missing info
                    </button>
                    
                    <button type="button" class="report-chip" data-category="Account & sign-in">
                        <i class="fa-solid fa-circle-user"></i> Account & sign-in
                    </button>
                    <button type="button" class="report-chip" data-category="Suggestion">
                        <i class="fa-solid fa-lightbulb"></i> Suggestion
                    </button>
                    <button type="button" class="report-chip" data-category="Other">
                        <i class="fa-solid fa-ellipsis"></i> Other
                    </button>
                </div>
                <input type="hidden" id="selectedCategory" value="Something isn't working">
            </div>

            <!-- What happened? -->
            <div class="report-section">
                <label class="report-label">What happened?</label>
                <div class="textarea-wrapper">
                    <textarea id="problemDescription" maxlength="1000" rows="4" placeholder="Example: The appointment didn't load after I tapped the book button." required></textarea>
                    <span class="char-count" id="charCount">0/1000</span>
                </div>
            </div>

            <!-- Email (Optional) -->
            <div class="report-section">
                <label class="report-label">Email <small>(optional, so we can reply)</small></label>
                <input type="email" id="userReportEmail" placeholder="you@example.com" class="report-input">
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitReportBtn" class="report-submit-btn">
                <i class="fa-solid fa-paper-plane"></i> Submit Report
            </button>
        </form>
    </div>
</div>

<style>
/* SMART VET CARE EXCLUSIVE LIGHT THEME */
.report-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 15px;
    box-sizing: border-box;
}

.report-modal-card {
    background: #ffffff;
    color: #1e293b;
    width: 100%;
    max-width: 480px;
    border-radius: 18px;
    padding: 24px;
    position: relative;
    box-shadow: 0 20px 40px rgba(23, 63, 129, 0.12);
    border: 1px solid #e2e8f0;
    font-family: 'Poppins', sans-serif;
    max-height: 90vh;
    overflow-y: auto;
}

.report-modal-close {
    position: absolute;
    top: 18px;
    right: 18px;
    background: #f1f5f9;
    border: none;
    color: #64748b;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.report-modal-close:hover {
    background: #fee2e2;
    color: #dc2626;
}

.report-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
}

.report-icon-box {
    background: #e0edff;
    color: #173F81;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.report-header h2 {
    font-size: 18px;
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #173F81;
}

.report-header p {
    font-size: 11.5px;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.report-section {
    margin-bottom: 16px;
}

.report-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

.report-chips-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.report-chip {
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #e2e8f0;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    font-family: 'Poppins', sans-serif;
}

.report-chip:hover {
    border-color: #173F81;
    color: #173F81;
    background: #f1f5f9;
}

.report-chip.active {
    background: #173F81;
    border-color: #173F81;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(23, 63, 129, 0.2);
}

.textarea-wrapper {
    position: relative;
}

textarea#problemDescription {
    width: 100%;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    color: #1e293b;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
    resize: none;
    font-family: 'Poppins', sans-serif;
}

textarea#problemDescription:focus {
    border-color: #173F81;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(23, 63, 129, 0.1);
}

.char-count {
    position: absolute;
    bottom: 8px;
    right: 12px;
    font-size: 10px;
    color: #94a3b8;
}

.report-input {
    width: 100%;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
    color: #1e293b;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

.report-input:focus {
    border-color: #173F81;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(23, 63, 129, 0.1);
}

.report-submit-btn {
    width: 100%;
    background: #173F81;
    color: #ffffff;
    border: none;
    padding: 12px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(23, 63, 129, 0.2);
}

.report-submit-btn:hover {
    background: #0f2b5a;
    transform: translateY(-1px);
}
</style>