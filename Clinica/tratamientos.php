<?php
$conexion = new mysqli("localhost", "root", "", "clinica");
if ($conexion->connect_error) {
    die("<tr><td colspan='5'>Error de conexión: " . $conexion->connect_error . "</td></tr>");
}

$sql = "SELECT t.Id_tratamiento, p.Nombre AS Paciente, t.Diagnostico, t.Descripcion, d.Nombre AS Medico, t.Dosis
        FROM tratamientos t
        LEFT JOIN pacientes p ON t.Id_paciente = p.Id_paciente
        LEFT JOIN doctores d ON t.Id_doctor = d.Id_doctor
        ORDER BY t.Id_tratamiento DESC";

$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila["Paciente"]) . "</td>";
        echo "<td>" . htmlspecialchars($fila["Diagnostico"]) . "</td>"; // Enfermedad
        echo "<td>" . htmlspecialchars($fila["Descripcion"]) . "</td>"; // Tratamiento
        echo "<td>" . htmlspecialchars($fila["Medico"]) . "</td>";
        echo "<td>" . htmlspecialchars($fila["Dosis"]) . "</td>"; // Dosis en lugar de Fecha
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No hay tratamientos registrados.</td></tr>";
}

$conexion->close();
?>
