<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>WhatsApp API Setup</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Configure WhatsApp Cloud API credentials for sending automated client bills and notifications.</p>

    <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 600px;">
        <form method="POST" action="<?= url('setup/storeWhatsappSetup') ?>">
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Provider Name</label>
                <input type="text" name="provider_name" value="<?= htmlspecialchars($whatsapp['provider_name'] ?? '') ?>" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">API Endpoint URL</label>
                <input type="text" name="api_url" value="<?= htmlspecialchars($whatsapp['api_url'] ?? '') ?>" placeholder="https://graph.facebook.com/v17.0/PHONE_NUMBER_ID/messages" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Phone Number ID</label>
                <input type="text" name="phone_number_id" value="<?= htmlspecialchars($whatsapp['phone_number_id'] ?? '') ?>" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Permanent Access Token</label>
                <input type="password" name="access_token" value="<?= htmlspecialchars($whatsapp['access_token'] ?? '') ?>" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.85rem; font-weight: 600;">Gateway Status</label>
                <select name="status" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                    <option value="Active" <?= ($whatsapp['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($whatsapp['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">Save WhatsApp Configuration</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>