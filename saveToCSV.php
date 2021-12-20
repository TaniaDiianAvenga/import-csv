<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(404);
    exit();
}

header('Content-Type: application/json');

$errors       = [];
$success      = false;
$expectedKeys = ['name', 'email', 'comment'];

$fileName = "data/data_" . date('m') . ".csv";

try {
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!empty($_POST['comment']) && strlen($_POST['comment']) > 150) {
        $errors[] = "Field comments should not exceed 150 characters.";
    }

    foreach ($expectedKeys as $key) {
        if (strlen($_POST[$key]) > 20 && $key != "comment") {
            $errors[] = "Field $key should not exceed 20 characters.";
        }
    }

    if (empty($errors)) {
        foreach ($expectedKeys as $key) {
            $formData[$key] = trim($_POST[$key]);
        }

        if (!file_exists('data')) {
            mkdir('data', 0777, true);
        }

        $fileOpen = fopen($fileName, "a");

        $formData['time'] = date('H:i:s d-m-Y');
        if (fputcsv($fileOpen, $formData, ';')) {
            $success = true;
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
} finally {
    if (isset($fileOpen)) {
        fclose($fileOpen);
    }
}
echo json_encode([
    'errors'  => $errors,
    'success' => $success
]);
