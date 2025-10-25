<?php
session_start();
require 'db.php';

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dates = $_POST['coh_date'] ?? [];
    $amounts = $_POST['amount'] ?? [];

    $stmt = $conn->prepare("INSERT INTO coh_records (date, amount, branch) VALUES (?, ?, 'Malvar')");
    $saved = false;

    for ($i = 0; $i < count($dates); $i++) {
        if (!empty($dates[$i]) && !empty($amounts[$i])) {
            $stmt->execute([$dates[$i], $amounts[$i]]);
            $saved = true;
        }
    }

    if ($saved) {
        $successMsg = "✅ COH data saved successfully!";
    } else {
        $errorMsg = "⚠️ No valid data entered.";
    }
}

// ✅ Fetch saved COH records for Malvar branch
$records = $conn->query("SELECT date, amount FROM coh_records WHERE branch = 'Malvar' ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Total COH - Malvar Branch</title>
<style>
    * { box-sizing: border-box; font-family: "Segoe UI", Roboto, Arial, sans-serif; }
    body {
        background: #f3f3f3; margin: 0; padding: 20px;
        display: flex; justify-content: center;
        opacity: 0; transition: opacity 0.5s ease-in-out;
    }
    body.loaded { opacity: 1; }
    .container {
        width: 100%; max-width: 480px; background: #fff;
        border-radius: 10px; padding: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center; background: #4caf50; color: white;
        padding: 8px; border-radius: 8px; font-size: 16px; margin: 0 0 15px;
    }
    .section {
        border: 1.5px solid #a5d6a7; border-radius: 8px;
        padding: 15px; margin-bottom: 15px; background: #fafafa;
    }
    h3 { font-size: 14px; font-weight: bold; color: #2e7d32; margin-bottom: 10px; }
    .row { display: flex; align-items: center; justify-content: space-between; gap: 6px; margin-bottom: 8px; }
    label { font-size: 13px; font-weight: 600; color: #333; width: 50px; }
    input[type="date"], input[type="number"] {
        padding: 6px; border: 1px solid #ccc; border-radius: 6px;
        font-size: 13px; width: 120px; text-align: center; transition: all 0.2s ease;
    }
    input[type="number"]:focus, input[type="date"]:focus {
        border-color: #4caf50; box-shadow: 0 0 4px rgba(76,175,80,0.4);
    }
    .total-box {
        display: flex; align-items: center; justify-content: center;
        border: 1px solid #90caf9; background: #e3f2fd;
        border-radius: 6px; height: 30px; width: 100%;
        font-weight: bold; font-size: 14px; color: #333;
    }
    .btn {
        display: inline-block; width: 100%; padding: 10px;
        border: none; border-radius: 6px; font-size: 14px;
        font-weight: 600; cursor: pointer; transition: background 0.3s;
    }
    .save-btn { background: #4caf50; color: white; margin-top: 10px; }
    .save-btn:hover { background: #388e3c; }
    .back-btn { background: #2196f3; color: white; margin-top: 10px; }
    .back-btn:hover { background: #1976d2; }
    .alert, .success {
        border-radius: 6px; margin-bottom: 10px; text-align: center; padding: 10px;
        font-weight: 500;
    }
    .alert {
        background: #fff8e1; color: #e65100; border: 1px solid #ffcc80;
    }
    .success {
        background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7;
    }
    table {
        width: 100%; border-collapse: collapse; margin-top: 15px;
    }
    th, td {
        border: 1px solid #ddd; padding: 6px; text-align: center; font-size: 13px;
    }
    th {
        background: #4caf50; color: white;
    }
</style>
</head>
<body>
<div class="container">
    <h2>TOTAL COH (Malvar Branch)</h2>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
        <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="section">
            <h3>DAILY COH INPUT</h3>
            <div id="rows">
                <div class="row">
                    <label>Date:</label>
                    <input type="date" name="coh_date[]" required>
                    <span>=</span>
                    <input type="number" name="amount[]" step="0.01" required placeholder="Amount">
                </div>
            </div>
            <button type="button" class="btn save-btn" style="background:#4caf50;" onclick="addRow()">+ ADD</button>
        </div>

        <div class="overall">
            <b>OVERALL TOTAL = </b>
            <div class="total-box" id="overall-total">0.00</div>
        </div>

        <button type="submit" class="btn save-btn">💾 SAVE</button>
        <button type="button" class="btn back-btn" onclick="goBack()">⬅ BACK</button>
    </form>

    <?php if ($records->rowCount() > 0): ?>
        <h3 style="margin-top: 20px; color:#2e7d32;">Saved COH Records</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            <?php while ($row = $records->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= number_format($row['amount'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:#888; margin-top:10px;">No COH records yet.</p>
    <?php endif; ?>
</div>

<script>
function formatNumber(num) {
    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function addRow() {
    const container = document.getElementById('rows');
    const div = document.createElement('div');
    div.className = 'row';
    div.innerHTML = `
        <label>Date:</label>
        <input type="date" name="coh_date[]" required>
        <span>=</span>
        <input type="number" name="amount[]" step="0.01" required placeholder="Amount">
    `;
    container.appendChild(div);
    attachListeners();
}

function computeTotal() {
    let total = 0;
    document.querySelectorAll('input[name="amount[]"]').forEach(i => {
        total += parseFloat(i.value || 0);
    });
    document.getElementById('overall-total').textContent = formatNumber(total);
}

function attachListeners() {
    document.querySelectorAll('input[name="amount[]"]').forEach(i => {
        i.removeEventListener('input', computeTotal);
        i.addEventListener('input', computeTotal);
    });
}

function goBack() {
    window.location.href = 'index.php'; // change if you have a different page name
}

window.onload = () => {
    document.body.classList.add('loaded');
    attachListeners();
};
</script>
</body>
</html>
