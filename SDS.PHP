<?php
// chicken_inventory.php
// Mobile-first chicken inventory & expenses module
// Requires a db.php that sets $conn as PDO. See sample SQL above to create tables.

require 'auth.php'; // optional: adjust to your auth setup
requireLogin();     // optional: remove if you don't use auth

// --- DB connection (expects $conn from db.php) ---
require_once 'db.php';

// Make sure tables exist (safe to run multiple times)
try {
    $conn->exec("
    CREATE TABLE IF NOT EXISTS chicken_inventory (
      id INT AUTO_INCREMENT PRIMARY KEY,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      regular_total DECIMAL(12,2) DEFAULT 0,
      large_total DECIMAL(12,2) DEFAULT 0,
      jumbo_total DECIMAL(12,2) DEFAULT 0,
      overall_total DECIMAL(12,2) DEFAULT 0
    );
    CREATE TABLE IF NOT EXISTS chicken_expenses (
      id INT AUTO_INCREMENT PRIMARY KEY,
      inventory_id INT NOT NULL,
      label VARCHAR(255),
      amount DECIMAL(12,2) DEFAULT 0,
      FOREIGN KEY (inventory_id) REFERENCES chicken_inventory(id) ON DELETE CASCADE
    );");
} catch (Exception $e) {
    // ignore creation errors if user lacks privileges; handle later
}

// Handle form POST
$errors = [];
$success_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_inventory'])) {
    // Collect totals (server-side validation)
    $regular_total = floatval($_POST['regular_total'] ?? 0);
    $large_total = floatval($_POST['large_total'] ?? 0);
    $jumbo_total = floatval($_POST['jumbo_total'] ?? 0);
    $overall_total = floatval($_POST['overall_total'] ?? 0);

    try {
        $stmt = $conn->prepare("INSERT INTO chicken_inventory (regular_total, large_total, jumbo_total, overall_total) VALUES (?, ?, ?, ?)");
        $stmt->execute([$regular_total, $large_total, $jumbo_total, $overall_total]);
        $inv_id = $conn->lastInsertId();

        // save expenses (if any)
        if (!empty($_POST['expense_label']) && is_array($_POST['expense_label'])) {
            $ins = $conn->prepare("INSERT INTO chicken_expenses (inventory_id, label, amount) VALUES (?, ?, ?)");
            foreach ($_POST['expense_label'] as $k => $lbl) {
                $lbl = trim($lbl);
                $amt = floatval($_POST['expense_amount'][$k] ?? 0);
                if ($lbl !== '' || $amt > 0) {
                    $ins->execute([$inv_id, $lbl, $amt]);
                }
            }
        }
        $success_msg = "Inventory saved successfully.";
    } catch (Exception $e) {
        $errors[] = "DB error: " . $e->getMessage();
    }
}

// Fetch previous records
$records = $conn->query("SELECT * FROM chicken_inventory ORDER BY created_at DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);

function format_money($v) {
    return number_format((float)$v, 2);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Chicken Inventory - Mobile</title>
  <style>
    :root{
      --accent: #3273ff;
      --muted:#777;
      --card:#fff;
      --bg:#f4f6fb;
      --radius:12px;
      --shadow: 0 8px 30px rgba(47,52,70,0.08);
    }
    html,body{height:100%;margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:#111}
    .wrap{max-width:820px;margin:14px auto;padding:12px}
    header{display:flex;align-items:center;gap:12px;padding:12px 6px}
    header .logo{width:60px;height:60px;background:linear-gradient(135deg,var(--accent),#6b8bff);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700}
    h1{font-size:18px;margin:0}
    .card{background:var(--card);border-radius:var(--radius);padding:14px;box-shadow:var(--shadow);margin-bottom:14px}
    /* sections */
    .section-title{font-weight:700;font-size:13px;margin-bottom:8px;color:#0a2540}
    .grid{display:grid;gap:8px}
    /* rows inside a size card */
    .size-card{border:2px solid #e6f2ff;padding:10px;border-radius:10px}
    .size-card h3{margin:0;font-size:13px;color:#0d3d83}
    .row{display:flex;gap:8px;align-items:center;margin-top:8px}
    .row label{width:70px;font-size:12px;color:var(--muted)}
    .qty,.price{flex:1;}
    input[type="number"], input[type="text"], input[type="date"] , textarea{
      width:100%;padding:8px;border-radius:8px;border:1px solid #dbe6ff;background:#fbfdff;box-sizing:border-box;
    }
    .calc-eq{width:28px;text-align:center;font-weight:700;color:var(--muted)}
    .total-box{width:90px;padding:8px;border-radius:8px;background:#f1f6ff;text-align:center;font-weight:700}
    .subtotal-line{display:flex;justify-content:flex-end;gap:8px;align-items:center;margin-top:8px}
    .big-total{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:8px;background:linear-gradient(90deg,#fff,#f7fbff);font-weight:800}
    /* expenses */
    .expenses-row{display:flex;gap:8px;margin-bottom:6px;align-items:center}
    .expenses-row input[type=text]{flex:3}
    .expenses-row input[type=number]{flex:1}
    .small-muted{font-size:12px;color:var(--muted);}
    .btn{display:inline-block;padding:10px 12px;border-radius:10px;border:none;background:var(--accent);color:#fff;font-weight:700;cursor:pointer}
    .btn.secondary{background:#fff;border:1px solid #d6e3ff;color:#0d3d83}
    .btn.ghost{background:transparent;border:1px dashed #c9d7ff;color:var(--muted)}
    /* responsive layout */
    @media(min-width:720px){
      .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
      .size-cols{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    }
    /* fade-in */
    .card, .size-card, .row { animation: fadeIn 0.5s ease both; }
    @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
    /* table of records */
    .records table{width:100%;border-collapse:collapse}
    .records th, .records td{padding:8px;border-bottom:1px solid #eee;text-align:left;font-size:13px}
    .muted-pill{background:#f3f5ff;padding:6px 8px;border-radius:8px;color:#0d3d83;font-weight:700}
    .danger{background:#ff6666;color:#fff}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <div class="logo">LOGO</div>
      <div>
        <h1>MARINDUQUENOS — Chicken Inventory</h1>
        <div class="small-muted">Mobile-friendly input & quick totals</div>
      </div>
    </header>

    <!-- messages -->
    <?php if(!empty($errors)): ?>
      <div class="card" style="border-left:4px solid #ff6b6b;">
        <strong>Error</strong>
        <div class="small-muted"><?php echo implode('<br>',$errors); ?></div>
      </div>
    <?php endif; ?>
    <?php if($success_msg): ?>
      <div class="card" style="border-left:4px solid #27ae60;">
        <strong>Success</strong>
        <div class="small-muted"><?=htmlspecialchars($success_msg)?></div>
      </div>
    <?php endif; ?>

    <form method="post" id="invForm">
      <div class="card">
        <div class="section-title">Date</div>
        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" />
      </div>

      <div class="card">
        <div class="section-title">CHICKEN INVENTORY</div>

        <div class="size-cols">
          <!-- REGULAR -->
          <div class="size-card" data-size="regular">
            <h3>REGULAR</h3>

            <div class="row">
              <label>WHOLE</label>
              <input type="number" min="0" step="1" class="qty" data-key="regular_whole_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="regular_whole_price" placeholder="Price">
              <div class="total-box" data-out="regular_whole_total">0.00</div>
            </div>

            <div class="row">
              <label>HALF</label>
              <input type="number" min="0" step="1" class="qty" data-key="regular_half_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="regular_half_price" placeholder="Price">
              <div class="total-box" data-out="regular_half_total">0.00</div>
            </div>

            <div class="row">
              <label>NECK</label>
              <input type="number" min="0" step="1" class="qty" data-key="regular_neck_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="regular_neck_price" placeholder="Price">
              <div class="total-box" data-out="regular_neck_total">0.00</div>
            </div>

            <div class="subtotal-line">
              <div style="flex:1"></div>
              <div>TOTAL</div>
              <div class="total-box" id="regular_subtotal">0.00</div>
            </div>
          </div>

          <!-- LARGE -->
          <div class="size-card" data-size="large">
            <h3>LARGE</h3>

            <div class="row">
              <label>WHOLE</label>
              <input type="number" min="0" step="1" class="qty" data-key="large_whole_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="large_whole_price" placeholder="Price">
              <div class="total-box" data-out="large_whole_total">0.00</div>
            </div>

            <div class="row">
              <label>HALF</label>
              <input type="number" min="0" step="1" class="qty" data-key="large_half_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="large_half_price" placeholder="Price">
              <div class="total-box" data-out="large_half_total">0.00</div>
            </div>

            <div class="subtotal-line">
              <div style="flex:1"></div>
              <div>TOTAL</div>
              <div class="total-box" id="large_subtotal">0.00</div>
            </div>
          </div>

          <!-- JUMBO -->
          <div class="size-card" data-size="jumbo">
            <h3>JUMBO</h3>

            <div class="row">
              <label>WHOLE</label>
              <input type="number" min="0" step="1" class="qty" data-key="jumbo_whole_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="jumbo_whole_price" placeholder="Price">
              <div class="total-box" data-out="jumbo_whole_total">0.00</div>
            </div>

            <div class="row">
              <label>HALF</label>
              <input type="number" min="0" step="1" class="qty" data-key="jumbo_half_qty" placeholder="Quantity">
              <div class="calc-eq">x</div>
              <input type="number" min="0" step="0.01" class="price" data-key="jumbo_half_price" placeholder="Price">
              <div class="total-box" data-out="jumbo_half_total">0.00</div>
            </div>

            <div class="subtotal-line">
              <div style="flex:1"></div>
              <div>TOTAL</div>
              <div class="total-box" id="jumbo_subtotal">0.00</div>
            </div>
          </div>
        </div>

        <div style="height:12px"></div>

        <div class="big-total">
          <div>OVERALL TOTAL =</div>
          <div class="muted-pill" id="overall_total_display">0.00</div>
        </div>

      </div>

      <!-- EXPENSES -->
      <div class="card">
        <div class="section-title">EXPENSES</div>
        <div id="expenses_container">
          <!-- dynamic rows inserted here -->
        </div>
        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="button" id="addExpense" class="btn ghost">+ Add Expense</button>
          <div style="flex:1"></div>
          <div style="display:flex;align-items:center;gap:8px">
            <div class="small-muted">TOTAL =</div>
            <div class="total-box" id="expenses_total">0.00</div>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-bottom:16px">
        <button type="submit" name="save_inventory" class="btn">Save Inventory</button>
        <button type="button" id="resetBtn" class="btn secondary">Reset</button>
      </div>
    </form>

    <!-- Saved records -->
    <div class="card records">
      <div class="section-title">Recent Records</div>
      <?php if(empty($records)): ?>
        <div class="small-muted">No saved inventory yet.</div>
      <?php else: ?>
        <table>
          <thead>
            <tr><th>#</th><th>Date</th><th>Regular</th><th>Large</th><th>Jumbo</th><th>Overall</th></tr>
          </thead>
          <tbody>
            <?php foreach($records as $i=>$r): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($r['created_at']) ?></td>
                <td><?= format_money($r['regular_total']) ?></td>
                <td><?= format_money($r['large_total']) ?></td>
                <td><?= format_money($r['jumbo_total']) ?></td>
                <td><?= format_money($r['overall_total']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

  </div>

<script>
// Utility
const money = v => Number(v||0).toFixed(2);

// Elements
const overallDisplay = document.getElementById('overall_total_display');
const regularSub = document.getElementById('regular_subtotal');
const largeSub = document.getElementById('large_subtotal');
const jumboSub = document.getElementById('jumbo_subtotal');
const expensesTotalEl = document.getElementById('expenses_total');
const expensesContainer = document.getElementById('expenses_container');
const invForm = document.getElementById('invForm');

// keys mapping
const keys = {
  regular: ['regular_whole', 'regular_half', 'regular_neck'],
  large: ['large_whole', 'large_half'],
  jumbo: ['jumbo_whole', 'jumbo_half']
};

// create an expense row
function addExpenseRow(label='', amount=''){
  const idx = Date.now();
  const div = document.createElement('div');
  div.className = 'expenses-row';
  div.innerHTML = `
    <input type="text" name="expense_label[]" placeholder="Description" value="${label}">
    <input type="number" step="0.01" min="0" name="expense_amount[]" placeholder="amount" value="${amount}">
    <button type="button" class="btn ghost" style="padding:6px 8px">Remove</button>
  `;
  div.querySelector('button').addEventListener('click', ()=>{ div.remove(); calcExpenses(); });
  div.querySelector('input[name="expense_amount[]"]').addEventListener('input', calcExpenses);
  expensesContainer.appendChild(div);
}

// initialize with 3 empty rows
addExpenseRow(); addExpenseRow(); addExpenseRow();

document.getElementById('addExpense').addEventListener('click', ()=> addExpenseRow());

// calculations: per item total and subtotals
function calcAll(){
  // per size subtotal
  let regular_total = 0, large_total=0, jumbo_total=0;

  // regular keys: whole, half, neck
  keys.regular.forEach(k=>{
    const qtyEl = document.querySelector(`[data-key="${k}_qty"]`);
    const priceEl = document.querySelector(`[data-key="${k}_price"]`);
    const outEl = document.querySelector(`[data-out="${k}_total"]`);
    if(qtyEl && priceEl && outEl){
      let t = (Number(qtyEl.value||0) * Number(priceEl.value||0));
      outEl.textContent = money(t);
      regular_total += t;
    }
  });
  // large
  keys.large.forEach(k=>{
    const qtyEl = document.querySelector(`[data-key="${k}_qty"]`);
    const priceEl = document.querySelector(`[data-key="${k}_price"]`);
    const outEl = document.querySelector(`[data-out="${k}_total"]`);
    if(qtyEl && priceEl && outEl){
      let t = (Number(qtyEl.value||0) * Number(priceEl.value||0));
      outEl.textContent = money(t);
      large_total += t;
    }
  });
  // jumbo
  keys.jumbo.forEach(k=>{
    const qtyEl = document.querySelector(`[data-key="${k}_qty"]`);
    const priceEl = document.querySelector(`[data-key="${k}_price"]`);
    const outEl = document.querySelector(`[data-out="${k}_total"]`);
    if(qtyEl && priceEl && outEl){
      let t = (Number(qtyEl.value||0) * Number(priceEl.value||0));
      outEl.textContent = money(t);
      jumbo_total += t;
    }
  });

  regularSub.textContent = money(regular_total);
  largeSub.textContent = money(large_total);
  jumboSub.textContent = money(jumbo_total);

  calcExpenses();

  const expenses_total = Number(expensesTotalEl.textContent || 0);
  const overall = regular_total + large_total + jumbo_total - expenses_total;
  overallDisplay.textContent = money(overall < 0 ? 0 : overall);

  // set hidden/submit values by injecting inputs (so server gets totals)
  setHidden('regular_total', money(regular_total));
  setHidden('large_total', money(large_total));
  setHidden('jumbo_total', money(jumbo_total));
  setHidden('overall_total', money(overall < 0 ? 0 : overall));
}

// set hidden input values for POST
function setHidden(name, value){
  let el = document.querySelector(`input[name="${name}"]`);
  if(!el){
    el = document.createElement('input');
    el.type='hidden'; el.name=name;
    invForm.appendChild(el);
  }
  el.value = value;
}

// calculate expenses total
function calcExpenses(){
  let total = 0;
  document.querySelectorAll('input[name="expense_amount[]"]').forEach(inp=>{
    total += Number(inp.value || 0);
  });
  expensesTotalEl.textContent = money(total);
  // keep totals updated
  setHidden('expenses_total', money(total));
  // also recalc overall to account for change
  // (overall uses subtract expenses)
  // but our overall calc uses calcExpenses result (call it)
}

// add listeners to inputs
document.querySelectorAll('.qty, .price').forEach(el=> el.addEventListener('input', calcAll));
document.querySelectorAll('input[name="expense_amount[]"]').forEach(el=> el.addEventListener('input', calcExpenses));

// reset
document.getElementById('resetBtn').addEventListener('click', ()=>{
  document.querySelectorAll('input[type=number]').forEach(i=> i.value='');
  document.querySelectorAll('input[type=text]').forEach(i=>{
    if(i.name!=='expense_label[]') i.value='';
  });
  expensesContainer.innerHTML='';
  addExpenseRow(); addExpenseRow(); // two empty
  calcAll();
});

// initial calc
calcAll();

</script>
</body>
</html>
