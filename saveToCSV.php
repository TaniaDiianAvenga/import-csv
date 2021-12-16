<?php

header('Content-Type: application/json');

$errors      = [];
$success     = false;
$expectedKeys = ['name', 'email', 'comment'];

$fileName = "data/data_" . date('m') . ".csv";

try {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!empty($_POST['comment']) && strlen($_POST['comment']) > 150) {
        $errors[] = "Field comments should not exceed 150 characters.";
    }

    if (empty($errors)) {
        foreach ($expectedKeys as $key) {
            $formData[$key] = $_POST[$key];
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
