<?php
// api.php
header("Content-Type: application/json; charset=UTF-8");
// Optional: kalau mau akses dari Postman/localhost lain, aktifkan CORS sementara:
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");

include "config.php";

$method = $_SERVER['REQUEST_METHOD'];

// Untuk preflight requests (OPTIONS)
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// fungsi bantu untuk mengirim response JSON
function send_json($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// baca input JSON (POST/PUT/DELETE body)
$input = json_decode(file_get_contents("php://input"), true);

// sanitize helper (very basic)
function esc($conn, $value) {
    return $conn->real_escape_string($value);
}

switch ($method) {
    case 'GET':
        // jika ada id di query string -> ambil 1 data, else ambil semua
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $res = $conn->query("SELECT * FROM products WHERE id = $id");
            if ($res && $row = $res->fetch_assoc()) {
                send_json($row);
            } else {
                send_json(["message" => "Data tidak ditemukan"], 404);
            }
        } else {
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            send_json($data);
        }
        break;

    case 'POST':
        // pastikan JSON dikirim: {"name":"...","price":12345}
        if (!empty($input['name']) && isset($input['price'])) {
            $name  = esc($conn, $input['name']);
            $price = esc($conn, $input['price']);
            $q = "INSERT INTO products (name, price) VALUES ('$name', '$price')";
            if ($conn->query($q)) {
                $id = $conn->insert_id;
                send_json(["message" => "Data berhasil ditambahkan", "id" => $id], 201);
            } else {
                send_json(["message" => "Gagal insert", "error" => $conn->error], 500);
            }
        } else {
            send_json(["message" => "Data tidak lengkap (name, price dibutuhkan)"], 400);
        }
        break;

    case 'PUT':
        // Update: url => api.php?id=1  (ID wajib di query)
        if (!isset($_GET['id'])) {
            send_json(["message" => "ID diperlukan untuk update (gunakan ?id=...)"], 400);
        }
        $id = intval($_GET['id']);
        if (!empty($input['name']) && isset($input['price'])) {
            $name  = esc($conn, $input['name']);
            $price = esc($conn, $input['price']);
            $q = "UPDATE products SET name='$name', price='$price' WHERE id=$id";
            if ($conn->query($q)) {
                if ($conn->affected_rows > 0) {
                    send_json(["message" => "Data berhasil diupdate"]);
                } else {
                    send_json(["message" => "Tidak ada perubahan atau data tidak ditemukan"], 404);
                }
            } else {
                send_json(["message" => "Gagal update", "error" => $conn->error], 500);
            }
        } else {
            send_json(["message" => "Data tidak lengkap (name, price dibutuhkan)"], 400);
        }
        break;

    case 'DELETE':
        // Delete: url => api.php?id=1
        if (!isset($_GET['id'])) {
            send_json(["message" => "ID diperlukan untuk delete (gunakan ?id=...)"], 400);
        }
        $id = intval($_GET['id']);
        $q = "DELETE FROM products WHERE id=$id";
        if ($conn->query($q)) {
            if ($conn->affected_rows > 0) {
                send_json(["message" => "Data berhasil dihapus"]);
            } else {
                send_json(["message" => "Data tidak ditemukan"], 404);
            }
        } else {
            send_json(["message" => "Gagal delete", "error" => $conn->error], 500);
        }
        break;

    default:
        send_json(["message" => "Method tidak diizinkan"], 405);
        break;
}
?>
