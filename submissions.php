<?php
include "config.php";
$result = $conn->query("SELECT * FROM submissions ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Saved Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
            font-family: 'Segoe UI', sans-serif;
        }
        .container { max-width: 900px; margin-top: 50px; }
        .card {
            border-radius: 20px;
            padding: 25px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        h2 { color: #ff4d6d; font-weight: bold; }
        .table { border-radius: 12px; overflow: hidden; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2 class="mb-4 text-center">📑 All Submissions</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Form Name</th>
                    <th>Data</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['form_name']) ?></td>
                        <td><pre><?= json_encode(json_decode($row['data']), JSON_PRETTY_PRINT) ?></pre></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
