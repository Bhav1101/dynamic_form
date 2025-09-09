<?php
include "config.php";

$fieldNames   = $_POST['field_names'] ?? [];
$fieldTypes   = $_POST['field_types'] ?? [];
$fieldOptions = $_POST['field_options'] ?? []; // NEW for dropdown/checkbox

$errors  = [];
$values  = [];
$success = "";

// Handle submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_form'])) {
    $activeFields = $_POST['active_fields'];
    $types        = $_POST['active_types'];

    foreach ($activeFields as $i => $field) {
        $type  = $types[$i];
        $value = $_POST[$field] ?? "";
        $values[$field] = is_array($value) ? $value : trim($value);

        // Validation rules
        switch ($type) {
            case "text":
                if (empty($value)) {
                    $errors[$field] = ucfirst($field) . " cannot be empty.";
                } elseif (!preg_match("/^[A-Za-z0-9\s\.\,\-']+$/", $value)) {
                    $errors[$field] = ucfirst($field) . " contains invalid characters.";
                }
                break;

            case "email":
                if (empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Invalid email format.";
                }
                break;

            case "number":
                if (!is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . " must be a valid number.";
                }
                break;

            case "password":
                if (strlen($value) < 6) {
                    $errors[$field] = "Password must be at least 6 characters.";
                }
                break;

            case "dropdown":
                if (empty($value)) {
                    $errors[$field] = "Please select an option.";
                }
                break;

            case "checkbox":
                if (empty($value)) {
                    $errors[$field] = "Please check at least one option.";
                }
                break;
        }
    }

    if (empty($errors)) {
        // Save as JSON in DB
        $formName = "Custom Form " . date("Y-m-d H:i:s");
        $jsonData = json_encode($values);

        $stmt = $conn->prepare("INSERT INTO submissions (form_name, data) VALUES (?, ?)");
        $stmt->bind_param("ss", $formName, $jsonData);
        $stmt->execute();
        $stmt->close();

        $success = "✅ Form submitted successfully!";
        $values = [];
    }

    // Restore field definitions after submit
    $fieldNames   = $_POST['orig_field_names'] ?? [];
    $fieldTypes   = $_POST['orig_field_types'] ?? [];
    $fieldOptions = $_POST['orig_field_options'] ?? [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generated Custom Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }
        .container { max-width: 800px; margin-top: 60px; }
        .card {
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.25);
            background: #ffffff;
            color: #333;
        }
        h2 { color: #ff4d6d; font-weight: bold; }
        .form-control, .form-select { border-radius: 12px; }
        .btn-success {
            background: #28a745;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-size: 18px;
            transition: 0.3s;
        }
        .btn-success:hover { background: #218838; transform: scale(1.05); }
        .field-box {
            background: #fff4f7;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 12px;
            transition: 0.3s;
        }
        .field-box:hover { background: #ffe4ec; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-5">
        <h2 class="text-center mb-4">📝 Fill Out Your Custom Form</h2>

        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?= $success ?></div>
        <?php endif; ?>

        <form method="post">
            <?php foreach ($fieldNames as $i => $name): ?>
                <?php 
                    $type    = $fieldTypes[$i]; 
                    $options = $fieldOptions[$i] ?? "";
                    $safeName = strtolower(preg_replace("/[^a-zA-Z0-9_]/", "_", $name));
                ?>

                <!-- preserve original definitions -->
                <input type="hidden" name="orig_field_names[]" value="<?= htmlspecialchars($name) ?>">
                <input type="hidden" name="orig_field_types[]" value="<?= $type ?>">
                <input type="hidden" name="orig_field_options[]" value="<?= htmlspecialchars($options) ?>">

                <!-- active fields for validation -->
                <input type="hidden" name="active_fields[]" value="<?= $safeName ?>">
                <input type="hidden" name="active_types[]" value="<?= $type ?>">

                <div class="mb-3 field-box">
                    <?php if ($type == "dropdown"): ?>
                        <label class="form-label"><?= ucfirst($name) ?></label>
                        <select class="form-select" name="<?= $safeName ?>">
                            <option value="">Select <?= ucfirst($name) ?></option>
                            <?php 
                                $opts = array_map('trim', explode(',', $options));
                                foreach ($opts as $opt): 
                                    if ($opt != ""): ?>
                                        <option value="<?= $opt ?>" <?= ($values[$safeName] ?? "")==$opt?"selected":"" ?>><?= $opt ?></option>
                                    <?php endif; 
                                endforeach; ?>
                        </select>

                    <?php elseif ($type == "checkbox"): ?>
                        <label class="form-label d-block"><?= ucfirst($name) ?></label>
                        <?php 
                            $opts = array_map('trim', explode(',', $options));
                            foreach ($opts as $opt): 
                                if ($opt != ""): 
                                    $checked = (is_array($values[$safeName] ?? []) && in_array($opt, $values[$safeName])) ? "checked" : "";
                        ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="<?= $safeName ?>[]" value="<?= $opt ?>" <?= $checked ?>>
                                <label class="form-check-label"><?= $opt ?></label>
                            </div>
                        <?php endif; endforeach; ?>

                    <?php else: ?>
                        <label class="form-label"><?= ucfirst($name) ?></label>
                        <input type="<?= $type ?>" 
                               class="form-control"
                               name="<?= $safeName ?>"
                               value="<?= htmlspecialchars($values[$safeName] ?? '') ?>">
                    <?php endif; ?>
                    <span class="error"><?= $errors[$safeName] ?? '' ?></span>
                </div>
            <?php endforeach; ?>

            <div class="text-center">
                <button type="submit" name="submit_form" class="btn btn-success">Submit 🚀</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
