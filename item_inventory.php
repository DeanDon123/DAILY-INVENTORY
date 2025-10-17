<?php
// item_inventory.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Item Inventory</title>
<style>
    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Roboto, Arial, sans-serif;
    }

    body {
        background: #f8fff8;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
    }

    .container {
        width: 100%;
        max-width: 380px;
    }

    /* Header */
    .header {
        background: #43a047;
        color: white;
        text-align: center;
        font-weight: bold;
        font-size: 14px;
        border-radius: 6px;
        padding: 8px;
        margin-bottom: 18px;
    }

    /* Boxed Sections */
    .box {
        background: white;
        border: 1.5px solid #8bc34a;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 15px;
        box-shadow: 0 0 3px rgba(0,0,0,0.05);
    }

    .box h3 {
        margin: 0 0 10px 0;
        font-size: 13px;
        font-weight: bold;
        color: #2e7d32;
        text-transform: uppercase;
    }

    /* Rows */
    .row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .row label {
        flex: 1;
        font-weight: 500;
        text-transform: uppercase;
    }

    .row span {
        margin: 0 5px;
        font-weight: bold;
        color: #333;
    }

    .row input {
        width: 90px;
        padding: 5px 6px;
        border: 1px solid #a5d6a7;
        border-radius: 5px;
        text-align: center;
        outline: none;
        font-size: 12px;
    }

    .row input:focus {
        border-color: #43a047;
        box-shadow: 0 0 3px #43a047;
    }

    /* Remarks Section */
    textarea {
        width: 100%;
        height: 80px;
        resize: none;
        border: 1px solid #a5d6a7;
        border-radius: 5px;
        padding: 6px;
        font-size: 13px;
        outline: none;
    }

    textarea:focus {
        border-color: #43a047;
        box-shadow: 0 0 3px #43a047;
    }

    /* Back Button */
    .back-btn {
        display: block;
        width: 100%;
        background: #2196f3;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 5px;
    }

    .back-btn:hover {
        background: #1976d2;
    }

    @media (max-width: 450px) {
        .container {
            width: 95%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">ITEM INVENTORY</div>

    <div class="box">
        <h3>ITEM INVENTORY</h3>
        <div class="row"><label>TOYO</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>ULING</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>MARINADE</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
    </div>

    <div class="box">
        <h3>PLASTIC</h3>
        <div class="row"><label>TOYO</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>ULING</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>MARINADE</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
    </div>

    <div class="box">
        <h3>ROLYO</h3>
        <div class="row"><label>TOYO</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>ULING</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>MARINADE</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
    </div>

    <div class="box">
        <h3>PAPER PLATE</h3>
        <div class="row"><label>TOYO</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>ULING</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
        <div class="row"><label>MARINADE</label><span>-</span><input type="number" placeholder="QUANTITY"></div>
    </div>

    <div class="box">
        <h3>REMARKS</h3>
        <textarea placeholder="Enter remarks here..."></textarea>
    </div>

    <button onclick="window.location.href='chicken_inventory.php'" class="back-btn">🔙 Back to Chicken Inventory</button>
</div>
</body>
</html>
