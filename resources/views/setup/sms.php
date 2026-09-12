<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>SMS Gateway Setup</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Configure bulk SMS provider API settings for client notifications.</p>

    <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 600px;">
        <form method="POST" action="<?= url('setup/storeSmsSetup') ?>">
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Provider Name</label>
                <input type="text" name="provider_name" value="<?= htmlspecialchars($sms['provider_name'] ?? '') ?>" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">SMS API URL</label>
                <input type="text" name="api_url" value="<?= htmlspecialchars($sms['api_url'] ?? '') ?>" placeholder="https://bulksms.com/api/send" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Sender ID / Masking Name</label>
                <input type="text" name="sender_id" value="<?= htmlspecialchars($sms['sender_id'] ?? '') ?>" placeholder="HK ISP" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">API Key / Token</label>
                <input type="password" name="api_key" value="<?= htmlspecialchars($sms['api_key'] ?? '') ?>" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Gateway Status</label>
                <select name="status" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                    <option value="Active" <?= ($sms['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($sms['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">Save SMS Configuration</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>