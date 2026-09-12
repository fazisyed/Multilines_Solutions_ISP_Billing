<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>MikroTik User List</h2>
    <p style="color: #64748b; margin-bottom: 20px;">View synchronized PPPoE and active secret users from your routers.</p>

    <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Router</th>
                    <th style="padding: 10px;">Username</th>
                    <th style="padding: 10px;">Profile</th>
                    <th style="padding: 10px;">IP Address</th>
                    <th style="padding: 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" style="padding: 15px; text-align: center; color: #64748b;">No synchronized MikroTik users found.</td></tr>
                <?php else: foreach ($users as $u): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px;"><?= $u['id'] ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u['router_name'] ?? 'N/A') ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u['username']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u['profile']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u['ip_address']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($u['status']) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>