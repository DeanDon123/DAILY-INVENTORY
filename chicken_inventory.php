<?php
// chicken_inventory.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chicken Inventory</title>
<style>
    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Roboto, Arial, sans-serif;
    }

    body {
        background: #f3f3f3;
        margin: 0;
        padding: 10px;
        display: flex;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
    }

    body.fade-in {
        opacity: 1;
    }

    .container {
        width: 100%;
        max-width: 430px;
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: scale(0.97);
        opacity: 0;
        transition: all 0.5s ease-in-out;
    }

    .container.show {
        transform: scale(1);
        opacity: 1;
    }

    /* 🔹 HEADER LAYOUT */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .left-side {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .logo-box {
        width: 70px;
        height: 35px;
        background: #ddd;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: bold;
        color: #444;
    }

    h2 {
        margin: 0;
        color: #333;
        font-size: 17px;
        font-weight: bold;
    }

    /* 🗓️ Calendar */
    .date-picker {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .date-picker label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }

    input[type="date"] {
        padding: 6px 10px;
        border: 1.5px solid #a5d6a7;
        border-radius: 8px;
        background: linear-gradient(to right, #e8f5e9, #ffffff);
        font-size: 13px;
        font-weight: 500;
        color: #333;
        transition: all 0.2s ease;
        outline: none;
    }

    input[type="date"]:focus {
        border-color: #43a047;
        background: #e8f5e9;
        box-shadow: 0 0 4px rgba(67,160,71,0.5);
    }

    h3 {
        background: #4caf50;
        color: white;
        padding: 6px;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 10px;
    }

    .section {
        border: 1.5px solid #a5d6a7;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        background: #fafafa;
        transition: 0.3s;
    }

    .section:hover {
        transform: scale(1.01);
        box-shadow: 0 0 10px rgba(76,175,80,0.15);
    }

    .row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 6px;
    }

    .row span {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
    }

    .row input[type="number"],
    .row input[type="text"] {
        flex: 1.2;
        text-align: center;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 13px;
        min-width: 60px;
        transition: 0.2s;
    }

    .row input:focus {
        border-color: #43a047;
        box-shadow: 0 0 4px rgba(67,160,71,0.5);
    }

    .total-box {
        flex: 0.9;
        background: #e0e0e0;
        border-radius: 6px;
        padding: 6px;
        text-align: center;
        font-weight: 600;
        width: 80px;
        font-size: 13px;
    }

    .total-row {
        background: #f1f8e9;
        padding: 4px;
        border-radius: 6px;
    }

    .overall {
        border: 1.5px solid #90caf9;
        background: #e3f2fd;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .expenses {
        border: 1.5px solid #ffcc80;
        background: #fff8e1;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .button-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 6px;
    }

    .button-row button {
        flex: 1;
        min-width: 100px;
        background: #2196f3;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* Ripple effect */
    .button-row button::after {
        content: "";
        position: absolute;
        width: 5px;
        height: 5px;
        background: rgba(255,255,255,0.6);
        display: block;
        transform: scale(0);
        border-radius: 50%;
        transition: transform 0.5s, opacity 1s;
        pointer-events: none;
    }

    .button-row button:active::after {
        transform: scale(20);
        opacity: 0;
        transition: 0s;
    }

    .button-row button:hover {
        background: #1976d2;
        transform: scale(1.03);
    }

    @media (max-width: 430px) {
        .header { flex-direction: column; align-items: flex-start; }
        .date-picker { margin-top: 6px; }
        .row { flex-wrap: wrap; }
        .total-box { width: 100%; }
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="left-side">
            <h2>MARINDUQUENOS</h2>
            <div class="logo-box">LOGO</div>
        </div>
        <div class="date-picker">
            <label for="date">📅</label>
            <input type="date" id="date">
        </div>
    </div>

    <h3>CHICKEN INVENTORY</h3>

    <!-- REGULAR -->
    <div class="section" id="regular">
        <h3>REGULAR</h3>
        <div class="row"><span>Whole</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row"><span>Half</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row"><span>Neck</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row total-row"><b>Total:</b><div class="total-box category-total">0.00</div></div>
    </div>

    <!-- LARGE -->
    <div class="section" id="large">
        <h3>LARGE</h3>
        <div class="row"><span>Whole</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row"><span>Half</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row total-row"><b>Total:</b><div class="total-box category-total">0.00</div></div>
    </div>

    <!-- JUMBO -->
    <div class="section" id="jumbo">
        <h3>JUMBO</h3>
        <div class="row"><span>Whole</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row"><span>Half</span><input type="number" class="qty"><input type="number" class="price"><div class="total-box total">0.00</div></div>
        <div class="row total-row"><b>Total:</b><div class="total-box category-total">0.00</div></div>
    </div>

    <!-- EXPENSES -->
    <div class="expenses">
        <h3>EXPENSES</h3>
        <div class="row"><input type="text" placeholder="Input field"><input type="number" class="expense" placeholder="Amount"><input type="text" placeholder="Input field"><input type="number" class="expense" placeholder="Amount"></div>
        <div class="row"><input type="text" placeholder="Input field"><input type="number" class="expense" placeholder="Amount"><input type="text" placeholder="Input field"><input type="number" class="expense" placeholder="Amount"></div>
        <div class="row total-row"><b>Total:</b><div class="total-box" id="expense-total">0.00</div></div>
    </div>

    <!-- OVERALL -->
    <div class="overall">
        <div class="row total-row"><b>OVERALL TOTAL:</b><div class="total-box" id="overall-total">0.00</div></div>
    </div>

    <!-- BUTTONS -->
    <div class="button-row">
        <button onclick="navigateTo('item_inventory.php')">ITEM INVENTORY</button>
        <button onclick="navigateTo('inventory_page.php')">CHICKEN INVENTORY</button>
        <button onclick="navigateTo('total_coh.php')">TOTAL COH</button>
    </div>
</div>

<script>
function formatNumber(num) {
    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function computeSection(section) {
    let rows = section.querySelectorAll('.row');
    let totals = [];
    rows.forEach(row => {
        let qty = row.querySelector('.qty');
        let price = row.querySelector('.price');
        let total = row.querySelector('.total');
        if (qty && price && total) {
            let value = (parseFloat(qty.value || 0) * parseFloat(price.value || 0));
            total.textContent = formatNumber(value);
            totals.push(value);
        }
    });
    let categoryTotal = section.querySelector('.category-total');
    if (categoryTotal) {
        let sum = totals.reduce((a,b)=>a+b,0);
        categoryTotal.textContent = formatNumber(sum);
    }
    computeOverall();
}

function computeExpenses() {
    let expenses = document.querySelectorAll('.expense');
    let total = 0;
    expenses.forEach(e => total += parseFloat(e.value || 0));
    document.getElementById('expense-total').textContent = formatNumber(total);
    computeOverall();
}

function computeOverall() {
    let categoryTotals = document.querySelectorAll('.category-total');
    let overall = 0;
    categoryTotals.forEach(t => overall += parseFloat(t.textContent.replace(/,/g, '') || 0));
    let expenses = parseFloat(document.getElementById('expense-total').textContent.replace(/,/g, '') || 0);
    let netTotal = overall - expenses;
    document.getElementById('overall-total').textContent = formatNumber(netTotal);
}

// Auto compute
document.querySelectorAll('.qty, .price').forEach(i => i.addEventListener('input', () => {
    let section = i.closest('.section');
    computeSection(section);
}));
document.querySelectorAll('.expense').forEach(i => i.addEventListener('input', computeExpenses));

// Auto set today's date
document.getElementById('date').value = new Date().toISOString().split('T')[0];

// 🔹 Smooth page fade in/out effect
window.addEventListener('load', () => {
    document.body.classList.add('fade-in');
    document.querySelector('.container').classList.add('show');
});

function navigateTo(url) {
    document.body.classList.remove('fade-in');
    document.querySelector('.container').classList.remove('show');
    setTimeout(() => {
        window.location.href = url;
    }, 500);
}
</script>
</body>
</html>
