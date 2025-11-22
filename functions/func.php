<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../config/connect.php";

function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

function createData($table, $data, $redirectSuccess, $redirectFail) {
    global $conn;
    $columns = implode(",", array_keys($data));
    $placeholders = implode(",", array_fill(0, count($data), "?"));
    $values = array_values($data);
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);
    $types = str_repeat("s", count($values));
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) {
        redirect_with_message($redirectSuccess, "Data berhasil ditambahkan");
    } else {
        redirect_with_message($redirectFail, "Gagal menambahkan: " . $stmt->error, "error");
    }
}

function updateData($table, $data, $where, $redirectSuccess, $redirectFail) {
    global $conn;
    $setParts = [];
    foreach ($data as $key => $value) {
        $setParts[] = "$key = ?";
    }
    $setQuery = implode(", ", $setParts);
    $whereParts = [];
    foreach ($where as $key => $value) {
        $whereParts[] = "$key = ?";
    }
    $whereQuery = implode(" AND ", $whereParts);
    $sql = "UPDATE $table SET $setQuery WHERE $whereQuery";
    $stmt = $conn->prepare($sql);
    $values = array_merge(array_values($data), array_values($where));
    $types = str_repeat("s", count($values));
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) {
        redirect_with_message($redirectSuccess, "Data berhasil diperbarui");
    } else {
        redirect_with_message($redirectFail, "Gagal update: " . $stmt->error, "error");
    }
}

function deleteData($table, $where, $redirectSuccess, $redirectFail) {
    global $conn;
    $whereParts = [];
    foreach ($where as $key => $value) {
        $whereParts[] = "$key = ?";
    }
    $whereQuery = implode(" AND ", $whereParts);
    $sql = "DELETE FROM $table WHERE $whereQuery";
    $stmt = $conn->prepare($sql);
    $values = array_values($where);
    $types = str_repeat("s", count($values));
    $stmt->bind_param($types, ...$values);
    if ($stmt->execute()) {
        redirect_with_message($redirectSuccess, "Data berhasil dihapus", "delete");
    } else {
        redirect_with_message($redirectFail, "Gagal hapus: " . $stmt->error, "error");
    }
}

function searchData($table, $columns, $keyword, $orderBy = 'id ASC') {
    global $conn;
    
    if (empty($keyword)) {
        $sql = "SELECT * FROM $table ORDER BY $orderBy";
        $result = $conn->query($sql);
    } else {
        $whereParts = [];
        foreach ($columns as $col) {
            $whereParts[] = "$col LIKE ?";
        }
        $whereQuery = implode(" OR ", $whereParts);
        
        $sql = "SELECT * FROM $table WHERE $whereQuery ORDER BY $orderBy";
        $stmt = $conn->prepare($sql);
        
        $searchParam = "%{$keyword}%";
        $types = str_repeat("s", count($columns));
        $params = array_fill(0, count($columns), $searchParam);
        
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    
    return $result->fetch_all(MYSQLI_ASSOC);
}