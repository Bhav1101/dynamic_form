<!DOCTYPE html>
<html>
<head>
    <title>Custom Form Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }
        .container { max-width: 800px; margin-top: 60px; }
        .card {
            border-radius: 20px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.2);
            background: #ffffff;
            color: #333;
        }
        h2 {
            font-weight: bold;
            color: #2575fc;
        }
        .form-control, .form-select {
            border-radius: 12px;
        }
        .btn-add {
            background: #28a745;
            border: none;
            border-radius: 12px;
            transition: 0.3s;
        }
        .btn-add:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .btn-danger {
            border-radius: 12px;
            transition: 0.3s;
        }
        .btn-danger:hover {
            transform: scale(1.05);
        }
        .btn-primary {
            background: #2575fc;
            border-radius: 12px;
            padding: 12px 25px;
            font-size: 18px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #1a5edb;
            transform: scale(1.05);
        }
        .field-box {
            background: #f1f5ff;
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 12px;
            transition: 0.3s;
        }
        .field-box:hover {
            background: #e2ebff;
        }
    </style>
    <script>
        function addField() {
            let container = document.getElementById("fields");
            let div = document.createElement("div");
            div.classList.add("row", "align-items-center", "field-box");

            div.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="field_names[]" class="form-control" placeholder="Field Name" required>
                </div>
                <div class="col-md-4">
                    <select name="field_types[]" class="form-select" onchange="toggleOptions(this)" required>
                        <option value="text">Text</option>
                        <option value="email">Email</option>
                        <option value="number">Number</option>
                        <option value="password">Password</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                <div class="col-md-3 options-box d-none mt-2">
                    <input type="text" name="field_options[]" class="form-control" placeholder="Enter options (comma separated)">
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">❌</button>
                </div>
            `;
            container.appendChild(div);
        }

        function toggleOptions(select) {
            let box = select.parentElement.parentElement.querySelector(".options-box");
            if (select.value === "dropdown" || select.value === "checkbox") {
                box.classList.remove("d-none");
            } else {
                box.classList.add("d-none");
                box.querySelector("input").value = "";
            }
        }
    </script>
</head>
<body>
<div class="container">
    <div class="card p-5">
        <h2 class="text-center mb-4">🌈 Build Your Custom Form</h2>
        <p class="text-center text-muted">Add fields with names, types, and (if needed) custom options for Dropdown/Checkbox.</p>
        
        <form method="post" action="form.php">
            <div id="fields"></div>
            
            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-add text-white" onclick="addField()">+ Add Field</button>
                <button type="submit" class="btn btn-primary">Generate Form 🚀</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
