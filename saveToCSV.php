<?php

header('Content-Type: application/json');

$errors      = [];
$success     = false;
$requireKeys = ['name', 'email', 'comment'];

const FILE_NAME = "data.csv";

try {

    foreach ($requireKeys as $key) {
        if (empty($_POST[$key])) {
            $errors[] = "Field $key is empty";
        } else {
            $formData[$key]   = $_POST[$key];
        }
    }

    if (empty($errors)) {
        $fileOpen = fopen(FILE_NAME, "a");

        $formData['time'] = date('H:i:s d-m-Y');
        if (fputcsv($fileOpen, $formData, ';')) {
            $success = true;
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
} finally {

    if ($fileOpen) {
        fclose($fileOpen);
    }
}

echo json_encode([
    'errors'  => $errors,
    'success' => $success
]);
