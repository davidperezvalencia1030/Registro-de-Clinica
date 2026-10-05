<?php
$conexion = new mysqli("localhost", "root", "", "clinica");
if ($conexion->connect_error) {
    die("<tr><td colspan='4'>Error de conexión: " . $conexion->connect_error . "</td></tr>");
}

$sql = "SELECT Id_cita, Nombre_paciente, Fecha FROM citas ORDER BY Fecha DESC";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $fechaCompleta = $fila["Fecha"];
        $fecha = date("Y-m-d", strtotime($fechaCompleta));
        $hora = date("H:i", strtotime($fechaCompleta));
        echo "<tr>";
        echo "<td>" . $fila["Id_cita"] . "</td>";
        echo "<td>" . htmlspecialchars($fila["Nombre_paciente"]) . "</td>";
        echo "<td>" . $fecha . "</td>";
        echo "<td>" . $hora . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No hay citas registradas.</td></tr>";
}

$conexion->close();
?>
