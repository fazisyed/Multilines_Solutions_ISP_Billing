<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="dashboard-container" style="padding: 24px;">
    <h2>Reseller List</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Manage registered reseller accounts.</p>
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Add Reseller</h3>
            <form method="POST" action="<?= url('reseller/resellerList') ?>">
                <div style="margin-bottom: 12px;"><label>Name</label><input type="text" name="name" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 12px;"><label>Company Name</label><input type="text" name="company_name" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 12px;"><label>Email</label><input type="email" name="email" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 12px;"><label>Mobile</label><input type="text" name="mobile" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 15px;"><label>Initial Balance</label><input type="number" step="0.01" name="balance" value="0.00" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Save Reseller</button>
            </form>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Resellers</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;"><th style="padding: 10px;">Name</th><th style="padding: 10px;">Company</th><th style="padding: 10px;">Mobile</th><th style="padding: 10px;">Balance</th></tr></thead>
                <tbody>
                    <?php if (empty($resellers)): ?><tr><td colspan="4" style="padding: 15px; text-align: center; color: #64748b;">No resellers found.</td></tr>
                    <?php else: foreach ($resellers as $r): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 10px;"><?= htmlspecialchars($r['name']) ?></td><td style="padding: 10px;"><?= htmlspecialchars($r['company_name']) ?></td><td style="padding: 10px;"><?= htmlspecialchars($r['mobile']) ?></td><td style="padding: 10px; font-weight:600; color:green;"><?= number_format($r['balance'], 2) ?></td></tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>