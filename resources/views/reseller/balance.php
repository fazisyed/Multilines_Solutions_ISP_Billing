<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="dashboard-container" style="padding: 24px;">
    <h2>Reseller Balance Management</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Add or deduct credits from reseller accounts.</p>
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Manage Balance</h3>
            <form method="POST" action="<?= url('reseller/resellerBalance') ?>">
                <div style="margin-bottom: 12px;"><label>Select Reseller</label>
                    <select name="reseller_id" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                        <option value="">-- Choose --</option>
                        <?php foreach ($resellers as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?> (Bal: <?= $r['balance'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom: 12px;"><label>Transaction Type</label>
                    <select name="type" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                        <option value="Add">Add Credit</option>
                        <option value="Deduct">Deduct Credit</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;"><label>Amount</label><input type="number" step="0.01" name="amount" required style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <div style="margin-bottom: 15px;"><label>Note</label><input type="text" name="note" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;"></div>
                <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">Process Transaction</button>
            </form>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h3>Recent Transactions</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead><tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;"><th style="padding: 10px;">Reseller</th><th style="padding: 10px;">Type</th><th style="padding: 10px;">Amount</th><th style="padding: 10px;">Note</th></tr></thead>
                <tbody>
                    <?php if (empty($transactions)): ?><tr><td colspan="4" style="padding: 15px; text-align: center; color: #64748b;">No transactions recorded.</td></tr>
                    <?php else: foreach ($transactions as $t): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 10px;"><?= htmlspecialchars($t['reseller_name']) ?></td><td style="padding: 10px; color:<?= $t['type'] === 'Add' ? 'green' : 'red' ?>;"><?= $t['type'] ?></td><td style="padding: 10px;"><?= number_format($t['amount'], 2) ?></td><td style="padding: 10px;"><?= htmlspecialchars($t['note']) ?></td></tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>