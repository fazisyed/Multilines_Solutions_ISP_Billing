<?php require_once __DIR__ . '/../partials/header.php'; ?>
<div class="dashboard-container" style="padding: 24px;">
    <h2>Reseller Balance Summary</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Overview of all reseller credit stats.</p>
    <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 10px;">Reseller Name</th>
                    <th style="padding: 10px;">Company</th>
                    <th style="padding: 10px;">Total Added</th>
                    <th style="padding: 10px;">Total Deducted</th>
                    <th style="padding: 10px;">Current Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($resellers)): ?>
                    <tr><td colspan="5" style="padding: 15px; text-align: center; color: #64748b;">No summary data found.</td></tr>
                <?php else: foreach ($resellers as $r): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 10px;"><?= htmlspecialchars($r['name']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($r['company_name']) ?></td>
                        <td style="padding: 10px; color: green;"><?= number_format($r['total_added'], 2) ?></td>
                        <td style="padding: 10px; color: red;"><?= number_format($r['total_deducted'], 2) ?></td>
                        <td style="padding: 10px; font-weight: bold;"><?= number_format($r['balance'], 2) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>