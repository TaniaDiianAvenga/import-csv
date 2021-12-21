<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
    http_response_code(404);
    exit();
}

header('Content-Type: application/json');

$errors       = [];
$success      = false;
$expectedKeysWithLength = [
    'name'    => 20,
    'email'   => 20,
    'comment' => 150
];

$fileName = "data/data_" . date('m') . ".csv";

function checkFieldLength($field, $maxLength, &$array)
{
    if (!empty($_POST[$field]) && strlen($_POST[$field]) > $maxLength) {
        $array[$field] = "Field $field should not exceed $maxLength characters.";
    }
}

try {
    foreach ($expectedKeysWithLength as $key=>$value) {
        checkFieldLength($key, $value, $errors);
    }

    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email address.";
    }

    if (empty($errors)) {
        foreach (array_keys($expectedKeysWithLength) as $key) {
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
