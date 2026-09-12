<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="dashboard-container" style="padding: 24px;">
    <h2>Reseller Package</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Configure bandwidth packages assigned to resellers.</p>
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Add Package</h3>
            <form method="POST" action="<?= url('reseller/resellerPackage') ?>">
                <div style="margin-bottom: 12px;"><label>Package Name</label><input type="text" name="package_name" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 12px;"><label>Bandwidth</label><input type="text" name="bandwidth" placeholder="10 Mbps" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 15px;"><label>Price</label><input type="number" step="0.01" name="price" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Save Package</button>
            </form>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Packages</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;"><th style="padding: 10px;">Package Name</th><th style="padding: 10px;">Bandwidth</th><th style="padding: 10px;">Price</th></tr></thead>
                <tbody>
                    <?php if (empty($packages)): ?><tr><td colspan="3" style="padding: 15px; text-align: center; color: #64748b;">No packages found.</td></tr>
                    <?php else: foreach ($packages as $p): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 10px;"><?= htmlspecialchars($p['package_name']) ?></td><td style="padding: 10px;"><?= htmlspecialchars($p['bandwidth']) ?></td><td style="padding: 10px;"><?= number_format($p['price'], 2) ?></td></tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>