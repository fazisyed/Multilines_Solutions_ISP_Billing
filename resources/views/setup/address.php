<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>Address Setup</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Manage operational districts, thanas, and service areas.</p>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Form to Add Address -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Add Location</h3>
            <form method="POST" action="<?= url('setup/storeAddress') ?>">
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">District</label>
                    <input type="text" name="district" placeholder="e.g. Khulna" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Thana / Upazila</label>
                    <input type="text" name="thana" placeholder="e.g. Sonadanga" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Area / Block</label>
                    <input type="text" name="area" placeholder="e.g. Gollamari" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Save Address</button>
            </form>
        </div>

        <!-- Address Table List -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Service Areas</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 10px;">District</th>
                        <th style="padding: 10px;">Thana</th>
                        <th style="padding: 10px;">Area</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($addresses)): ?>
                        <tr><td colspan="3" style="padding: 15px; text-align: center; color: #64748b;">No service locations added yet.</td></tr>
                    <?php else: foreach ($addresses as $a): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 10px;"><?= htmlspecialchars($a['district']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($a['thana']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($a['area']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>