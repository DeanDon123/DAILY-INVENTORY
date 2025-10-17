<?php
// chicken_balance.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chicken Balance</title>
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
    }

    .container {
        width: 100%;
        max-width: 430px;
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    h3 {
        background: #4caf50;
        color: white;
        padding: 6px;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
        margin: 10px 0;
    }

    .section {
        border: 1.5px solid #81c784;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        background: #fafafa;
    }

    .row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .row span {
        flex: 1;
    }

    .row input {
        flex: 1;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 6px;
        text-align: center;
        font-size: 13px;
    }

    .total-box {
        flex: 1;
        background: #e0e0e0;
        border-radius: 6px;
        padding: 6px;
        text-align: center;
        font-weight: 600;
        font-size: 13px;
    }

    .overall {
        border: 1.5px solid #81c784;
        border-radius: 8px;
        padding: 10px;
        background: #f1f8e9;
        text-align: center;
        font-weight: 600;
    }

    .button-row {
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }

    button {
        background: #2196f3;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s;
        font-size: 14px;
        width: 100%;
    }

    button:hover {
        background: #1976d2;
    }
</style>
</head>
<body>

<div class="container">
    <h3>CHICKEN INVENTORY</h3>

    <!-- REGULAR -->
    <div class="section" id="regular">
        <h4>REGULAR</h4>
        <div class="row"><span>BEGINNING -</span><input type="number" class="begin" data-type="regular"></div>
        <div class="row"><span>SALES -</span><input type="number" class="sales" data-type="regular"></div>
        <div class="row"><span>ENDING -</span><div class="total-box end" id="end-regular">0</div></div>
    </div>

    <!-- LARGE -->
    <div class="section" id="large">
        <h4>LARGE</h4>
        <div class="row"><span>BEGINNING -</span><input type="number" class="begin" data-type="large"></div>
        <div class="row"><span>SALES -</span><input type="number" class="sales" data-type="large"></div>
        <div class="row"><span>ENDING -</span><div class="total-box end" id="end-large">0</div></div>
    </div>

    <!-- JUMBO -->
    <div class="section" id="jumbo">
        <h4>JUMBO</h4>
        <div class="row"><span>BEGINNING -</span><input type="number" class="begin" data-type="jumbo"></div>
        <div class="row"><span>SALES -</span><input type="number" class="sales" data-type="jumbo"></div>
        <div class="row"><span>ENDING -</span><div class="total-box end" id="end-jumbo">0</div></div>
    </div>

    <!-- LEFT -->
    <div class="section" id="left">
        <h4>LEFT</h4>
        <div class="row"><span>REGULAR -</span><div class="total-box" id="left-regular">0</div></div>
        <div class="row"><span>LARGE -</span><div class="total-box" id="left-large">0</div></div>
        <div class="row"><span>JUMBO -</span><div class="total-box" id="left-jumbo">0</div></div>
    </div>

    <!-- OVERALL -->
    <div class="overall">
        OVER ALL ENDING = <span id="overall">0</span>
    </div>

    <!-- Back Button -->
    <div class="button-row">
        <button onclick="window.location.href='chicken_inventory.php'">⬅️ Back to Chicken Inventory</button>
    </div>
</div>

<script>
function updateEnding() {
    const types = ['regular', 'large', 'jumbo'];
    let overall = 0;

    types.forEach(type => {
        const begin = parseFloat(document.querySelector(`.begin[data-type="${type}"]`)?.value || 0);
        const sales = parseFloat(document.querySelector(`.sales[data-type="${type}"]`)?.value || 0);
        const end = Math.max(begin - sales, 0);
        document.getElementById(`end-${type}`).textContent = end;
        document.getElementById(`left-${type}`).textContent = end;
        overall += end;
    });

    document.getElementById('overall').textContent = overall;
}

document.querySelectorAll('.begin, .sales').forEach(input => {
    input.addEventListener('input', updateEnding);
});
</script>

</body>
</html>
 