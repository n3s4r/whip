<?php
$file = "count.txt";
$pin = "1234"; // <-- Change PIN here

// Make sure file exists
if (!file_exists($file)) {
    file_put_contents($file, "0");
}

$action = isset($_GET['action']) ? $_GET['action'] : "";

switch ($action) {
    case "get":
        $count = (int) file_get_contents($file);
        echo json_encode(["count" => $count]);
        break;

    case "hit":
        $count = (int) file_get_contents($file);
        $count++;
        file_put_contents($file, $count);
        echo json_encode(["count" => $count]);
        break;

    case "reset":
        $enteredPin = $_GET['pin'] ?? "";
        if ($enteredPin === $pin) {
            file_put_contents($file, "0");
            echo json_encode(["success" => true, "count" => 0]);
        } else {
            echo json_encode(["success" => false, "error" => "Wrong PIN"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
}
