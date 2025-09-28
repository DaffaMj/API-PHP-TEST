<?php
include "config.php";

// ambil semua data produk
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple API Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: #2d89ef;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 30px auto;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background: #2d89ef;
            color: white;
        }
        table tr:hover {
            background: #f1f1f1;
        }
        .endpoint {
            background: #eaf3ff;
            padding: 10px;
            margin-top: 20px;
            border-left: 4px solid #2d89ef;
            font-family: monospace;
        }
        footer {
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            background: #eee;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>🚀 Simple API is Running</h1>
        <p>PHP + MySQL REST API Example</p>
    </header>

    <div class="container">
        <h2>📦 Data Produk</h2>
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Tidak ada data produk.</p>
        <?php } ?>

        <div class="endpoint">
            🔗 Test endpoint API:  
            <br>
            <code>http://localhost/simple_api/api.php</code>
        </div>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Simple API Example - Dibuat dengan ❤️ pakai PHP & MySQL
    </footer>
</body>
</html>
