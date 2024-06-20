<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file']) && isset($_POST['modulo'])) {
    // Directorio donde se guardarán los archivos
    $uploadDir = '../templates/';

    // Asegúrate de que el directorio exista
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Obtén el nombre del archivo del campo 'modulo'
    $modulo = $_POST['modulo'];

    // Obtén la extensión del archivo original
    $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    // Construye el nombre y la ruta completa del archivo
    $uploadFile = $uploadDir . $modulo . '.' . $fileExtension;

    // Mueve el archivo del directorio temporal al directorio de destino
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $msg = "Excel " . $_POST["modulo"] . " saved";
    } else {
        $msg = "Error saving excel";
    }
} else {
    $msg = "No file uploaded or modulo not selected";
}
header('Location: ../templates.php?msg=' . $msg);
