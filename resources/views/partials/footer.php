</div> <!-- Close main content padding wrapper -->
        
        <footer style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 20px 30px; margin-top: auto; color: #64748b; font-size: 0.85rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <strong>Multilines Solutions Private Limited</strong>[cite: 1] — C-7, Huma Town, Jinnah Avenue, Karachi, Pakistan[cite: 1]
            </div>
            <div>
                Phone: +(92) 21 3411 6294[cite: 1] | Email: Info@multilinesolutions.com[cite: 1]
            </div>
        </footer>
    </main>
</div>

<script src="<?= asset('js/dashboard.js') ?>?v=<?= time() ?>"></script>
<?php if (strpos($path ?? '', '/customer/search') !== false): ?>
    <script src="<?= asset('js/customer.js') ?>"></script>
<?php endif; ?>

<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr(".date-picker", {
            dateFormat: "Y-m-d", 
            altInput: true,
            altFormat: "d/m/Y",  
            allowInput: true,
            placeholder: "DD/MM/YYYY"
        });
    });
</script>
</body>
</html>