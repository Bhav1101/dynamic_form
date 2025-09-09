<?php
include("db_connect.php");
$result = mysqli_query($conn, "SELECT * FROM form_fields");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🛍 Clothing Order Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #ff9a9e, #fad0c4);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .form-card {
      max-width: 650px;
      margin: 50px auto;
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      text-align: center;
      color: #ff4b2b;
      margin-bottom: 20px;
      font-weight: bold;
    }
    .form-label {
      font-weight: 600;
      color: #444;
    }
    .form-control, .form-select {
      border-radius: 12px;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
      border-color: #ff4b2b;
      box-shadow: 0 0 8px rgba(255,75,43,0.5);
    }
    .btn-submit {
      background: linear-gradient(90deg, #ff4b2b, #ff416c);
      border: none;
      padding: 12px;
      font-size: 18px;
      font-weight: bold;
      color: #fff;
      border-radius: 12px;
      transition: all 0.3s;
    }
    .btn-submit:hover {
      background: linear-gradient(90deg, #ff416c, #ff4b2b);
      transform: scale(1.05);
    }
    .icon-label i {
      color: #ff4b2b;
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="form-card">
    <h2><i class="fas fa-tshirt"></i> Clothing Order Form</h2>
    <form method="POST" action="submit_form.php">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="mb-3">
          <label class="form-label icon-label">
            <?php 
              if (stripos($row['label'], 'Name') !== false) echo "<i class='fas fa-user'></i>";
              if (stripos($row['label'], 'Email') !== false) echo "<i class='fas fa-envelope'></i>";
              if (stripos($row['label'], 'Phone') !== false) echo "<i class='fas fa-phone'></i>";
              if (stripos($row['label'], 'Clothing') !== false) echo "<i class='fas fa-tshirt'></i>";
              if (stripos($row['label'], 'Size') !== false) echo "<i class='fas fa-ruler'></i>";
              if (stripos($row['label'], 'Color') !== false) echo "<i class='fas fa-palette'></i>";
              if (stripos($row['label'], 'Quantity') !== false) echo "<i class='fas fa-sort-numeric-up'></i>";
              if (stripos($row['label'], 'Address') !== false) echo "<i class='fas fa-home'></i>";
            ?>
            <?= htmlspecialchars($row['label']) ?>
            <?php if ($row['required']) echo "<span class='text-danger'>*</span>"; ?>
          </label>

          <?php
          $options = $row['options'] ? explode(",", $row['options']) : [];
          switch ($row['type']) {
            case 'textarea':
              echo "<textarea name='field_{$row['id']}' class='form-control' " . ($row['required'] ? "required" : "") . "></textarea>";
              break;

            case 'select':
              echo "<select name='field_{$row['id']}' class='form-select' " . ($row['required'] ? "required" : "") . ">";
              echo "<option value=''>--Select--</option>";
              foreach ($options as $opt) {
                $safeOpt = htmlspecialchars(trim($opt));
                echo "<option value='{$safeOpt}'>{$safeOpt}</option>";
              }
              echo "</select>";
              break;

            case 'radio':
              foreach ($options as $opt) {
                $safeOpt = htmlspecialchars(trim($opt));
                echo "<div class='form-check'>
                        <input class='form-check-input' type='radio' name='field_{$row['id']}' value='{$safeOpt}' " . ($row['required'] ? "required" : "") . ">
                        <label class='form-check-label'>{$safeOpt}</label>
                      </div>";
              }
              break;

            case 'checkbox':
              foreach ($options as $opt) {
                $safeOpt = htmlspecialchars(trim($opt));
                echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='field_{$row['id']}[]' value='{$safeOpt}'>
                        <label class='form-check-label'>{$safeOpt}</label>
                      </div>";
              }
              break;

            default:
              echo "<input type='" . htmlspecialchars($row['type']) . "' name='field_{$row['id']}' class='form-control' " . ($row['required'] ? "required" : "") . ">";
          }
          ?>
        </div>
      <?php } ?>
      <button type="submit" class="btn-submit w-100"><i class="fas fa-check-circle"></i> Submit Order</button>
    </form>
  </div>
</body>
</html>
