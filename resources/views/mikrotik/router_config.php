<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>MikroTik Router Configuration</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Manage your connected MikroTik API routers.</p>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Form to Add Router -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Add New Router</h3>
            <form method="POST" action="<?= url('mikrotik/storeRouter') ?>">
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Router Name</label>
                    <input type="text" name="router_name" class="form-control" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">IP Address</label>
                    <input type="text" name="ip_address" class="form-control" placeholder="192.168.88.1" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">API Port</label>
                    <input type="number" name="api_port" value="8728" class="form-control" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Username</label>
                    <input type="text" name="username" class="form-control" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 0.85rem; font-weight: 600;">Password</label>
                    <input type="password" name="password" class="form-control" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <button type="submit" class="btn-primary" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Save Router</button>
            </form>
        </div>

        <!-- Routers Table List -->
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3 style="margin-bottom: 15px; font-size: 1rem;">Configured Routers</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 10px;">Name</th>
                        <th style="padding: 10px;">IP Address</th>
                        <th style="padding: 10px;">Port</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($routers)): ?>
                        <tr><td colspan="5" style="padding: 15px; text-align: center; color: #64748b;">No routers configured yet.</td></tr>
                    <?php else: foreach ($routers as $r): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 10px;"><?= htmlspecialchars($r['router_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($r['ip_address']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($r['api_port']) ?></td>
                            <td style="padding: 10px;"><span style="color: green; font-weight: 600;"><?= htmlspecialchars($r['status']) ?></span></td>
                            <td style="padding: 10px;">
                                <a href="<?= url('mikrotik/deleteRouter/' . $r['id']) ?>" onclick="return confirm('Are you sure?');" style="color: #ef4444; text-decoration: none;">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>