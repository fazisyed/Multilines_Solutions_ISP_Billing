<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>Payment Settings Setup</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Configure online payment gateways (bKash, SSLCommerz, etc.).</p>

    <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 10px;">Gateway Name</th>
                    <th style="padding: 10px;">API Key / Merchant ID</th>
                    <th style="padding: 10px;">Secret Key / Password</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gateways as $g): ?>
                <form method="POST" action="<?= url('setup/storePaymentSettings') ?>">
                    <input type="hidden" name="id" value="<?= $g['id'] ?>">
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px; font-weight: 600;"><?= htmlspecialchars($g['gateway_name']) ?></td>
                        <td style="padding: 10px;"><input type="text" name="api_key" value="<?= htmlspecialchars($g['api_key'] ?? '') ?>" placeholder="Enter API Key" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:4px;"></td>
                        <td style="padding: 10px;"><input type="password" name="secret_key" value="<?= htmlspecialchars($g['secret_key'] ?? '') ?>" placeholder="Enter Secret Key" style="width:100%; padding:6px; border:1px solid #cbd5e1; border-radius:4px;"></td>
                        <td style="padding: 10px;">
                            <select name="status" style="padding:6px; border:1px solid #cbd5e1; border-radius:4px;">
                                <option value="Active" <?= $g['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Inactive" <?= $g['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </td>
                        <td style="padding: 10px;">
                            <button type="submit" style="background:#3b82f6; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer;">Update</button>
                        </td>
                    </tr>
                </form>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>