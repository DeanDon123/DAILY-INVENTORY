<?php
// total_coh.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Total COH</title>
<style>
    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Roboto, Arial, sans-serif;
    }

    body {
        background: #f3f3f3;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    body.loaded {
        opacity: 1;
    }

    .container {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        background: #4caf50;
        color: white;
        padding: 8px;
        border-radius: 8px;
        font-size: 16px;
        margin: 0 0 15px;
    }

    .section {
        border: 1.5px solid #a5d6a7;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fafafa;
    }

    h3 {
        font-size: 14px;
        font-weight: bold;
        color: #2e7d32;
        margin-bottom: 10px;
    }

    .row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 8px;
    }

    label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        width: 50px;
    }

    input[type="date"], input[type="number"] {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
        width: 120px;
        text-align: center;
        transition: all 0.2s ease;
    }

    input[type="number"]:focus, input[type="date"]:focus {
        border-color: #4caf50;
        box-shadow: 0 0 4px rgba(76,175,80,0.4);
    }

    .total-box {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #90caf9;
        background: #e3f2fd;
        border-radius: 6px;
        height: 30px;
        width: 100%;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }

    .add-btn {
        display: inline-block;
        padding: 6px 10px;
        background: #4caf50;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .add-btn:hover {
        background: #388e3c;
    }

    .overall {
        border: 1.5px solid #a5d6a7;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background: #f1f8e9;
    }

    .back-btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px;
        background: #2196f3;
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 15px;
        font-size: 14px;
        position: relative;
        overflow: hidden;
    }

    .back-btn:hover {
        background: #1976d2;
    }

    /* 🔵 Ripple effect */
    .back-btn::after {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.5);
        border-radius: 50%;
        transform: scale(0);
        opacity: 0;
        transition: transform 0.6s, opacity 1s;
    }

    .back-btn:active::after {
        width: 200%;
        height: 200%;
        transform: scale(1);
        opacity: 1;
        transition: 0s;
    }
</style>
</head>
<body>
<div class="container">
    <h2>TOTAL COH</h2>

    <div class="section">
        <h3>DAILY COH</h3>
        <div id="rows">
            <div class="row">
                <label>Date:</label>
                <input type="date" class="coh-date">
                <span>=</span>
                <input type="number" class="amount" placeholder="Amount">
            </div>
            <div class="row">
                <label>Date:</label>
                <input type="date" class="coh-date">
                <span>=</span>
                <input type="number" class="amount" placeholder="Amount">
            </div>
            <div class="row">
                <label>Date:</label>
                <input type="date" class="coh-date">
                <span>=</span>
                <input type="number" class="amount" placeholder="Amount">
            </div>
        </div>
        <button class="add-btn" onclick="addRow()">+ ADD</button>
    </div>

    <div class="overall">
        <b>OVERALL TOTAL = </b>
        <div class="total-box" id="overall-total">0.00</div>
    </div>

    <button class="back-btn" onclick="goBack()">⬅ Back to Chicken Inventory</button>
</div>

<script>
function formatNumber(num) {
    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Add new COH row
function addRow() {
    const container = document.getElementById('rows');
    const div = document.createElement('div');
    div.className = 'row';
    div.innerHTML = `
        <label>Date:</label>
        <input type="date" class="coh-date">
        <span>=</span>
        <input type="number" class="amount" placeholder="Amount">
    `;
    container.appendChild(div);
    attachListeners(); // make new inputs responsive
}

// Compute total
function computeTotal() {
    let total = 0;
    document.querySelectorAll('.amount').forEach(i => {
        total += parseFloat(i.value || 0);
    });
    document.getElementById('overall-total').textContent = formatNumber(total);
}

// Attach listeners
function attachListeners() {
    document.querySelectorAll('.amount').forEach(i => {
        i.removeEventListener('input', computeTotal);
        i.addEventListener('input', computeTotal);
    });
}

// Back button with fade effect
function goBack() {
    document.body.classList.remove('loaded');
    setTimeout(() => window.location.href = 'inventory_page.php', 300);
}

// On load
window.onload = () => {
    document.body.classList.add('loaded');
    attachListeners();
};
</script>
</body>
</html>
