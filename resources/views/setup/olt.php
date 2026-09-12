<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>OLT Setup</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Configure optical line terminals (OLT) for fiber distribution.</p>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Form to Add OLT -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Add New OLT</h3>
            <form method="POST" action="<?= url('setup/storeOlt') ?>">
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">OLT Name</label>
                    <input type="text" name="olt_name" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">IP Address</label>
                    <input type="text" name="ip_address" placeholder="192.168.1.100" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Port Count</label>
                    <input type="number" name="port_count" value="8" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Location / Building</label>
                    <input type="text" name="location" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Save OLT</button>
            </form>
        </div>

        <!-- OLT Table List -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Configured OLTs</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 10px;">Name</th>
                        <th style="padding: 10px;">IP Address</th>
                        <th style="padding: 10px;">Ports</th>
                        <th style="padding: 10px;">Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($olts)): ?>
                        <tr><td colspan="4" style="padding: 15px; text-align: center; color: #64748b;">No OLTs configured yet.</td></tr>
                    <?php else: foreach ($olts as $o): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 10px;"><?= htmlspecialchars($o['olt_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($o['ip_address']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($o['port_count']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($o['location']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>