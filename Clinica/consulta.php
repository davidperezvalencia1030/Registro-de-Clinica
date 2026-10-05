<?php
header('Content-Type: application/json');
$conexion = new mysqli("localhost", "root", "", "clinica");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

if (!isset($_GET['nombre'])) {
    echo json_encode([]);
    exit;
}

$nombre = $_GET['nombre'];
$sql = "SELECT Id_cita, Nombre_paciente, Fecha FROM citas WHERE Nombre_paciente LIKE ?";
$stmt = $conexion->prepare($sql);
$busqueda = "%" . $nombre . "%";
$stmt->bind_param("s", $busqueda);
$stmt->execute();
$result = $stmt->get_result();

$citas = [];
while ($row = $result->fetch_assoc()) {
    $citas[] = $row;
}

echo json_encode($citas);

$stmt->close();
$conexion->close();
?>
