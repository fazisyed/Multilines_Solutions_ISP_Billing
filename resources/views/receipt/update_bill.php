<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="dashboard-container" style="padding: 24px;">
    <h2>Update Customer Bill</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Modify monthly rent and due balances for active customer accounts.</p>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?>" style="padding: 12px; margin-bottom: 15px; border-radius: 6px; background: <?= $messageType === 'success' ? '#f0fdf4' : '#fef2f2' ?>; color: <?= $messageType === 'success' ? '#15803d' : '#b91c1c' ?>;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div style="background: white; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; max-width: 700px;">
        <form method="POST" action="<?= url('receipt/updateBill') ?>">
            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 5px;">Select Customer</label>
                <select name="customer_id" id="customerSelect" class="form-control" required style="width:100%; padding:9px; border:1px solid #cbd5e1; border-radius:6px;" onchange="populateCustomerData(this)">
                    <option value="">-- Choose Customer --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>" data-rent="<?= $c['monthly_rent'] ?>" data-due="<?= $c['due_amount'] ?>">
                            [ID: <?= $c['id'] ?>] <?= htmlspecialchars($c['full_name']) ?> (<?= htmlspecialchars($c['mobile_no']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 5px;">Monthly Rent</label>
                <input type="number" step="0.01" name="monthly_rent" id="monthlyRent" required style="width:100%; padding:9px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.85rem; font-weight: 600; display: block; margin-bottom: 5px;">Due Amount</label>
                <input type="number" step="0.01" name="due_amount" id="dueAmount" required style="width:100%; padding:9px; border:1px solid #cbd5e1; border-radius:6px;">
            </div>

            <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">Update Bill Information</button>
        </form>
    </div>
</div>

<script>
function populateCustomerData(select) {
    const option = select.options[select.selectedIndex];
    const rent = option.getAttribute('data-rent') || '';
    const due = option.getAttribute('data-due') || '';
    
    document.getElementById('monthlyRent').value = rent;
    document.getElementById('dueAmount').value = due;
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>