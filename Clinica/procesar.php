<?php
$conexion = new mysqli("localhost", "root", "", "clinica");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$nombre = $_POST['Nombre_paciente'];
$telefono = $_POST['Telefono'];
$fecha = $_POST['Fecha'];

// 1. Insertar paciente en la tabla 'pacientes'
$sqlPaciente = "INSERT INTO pacientes (Nombre_paciente, Telefono) VALUES (?, ?)";
$stmtPaciente = $conexion->prepare($sqlPaciente);

// Validar si la preparación fue exitosa
$sqlPaciente = "INSERT INTO pacientes (Nombre, Telefono) VALUES (?, ?)";
$stmtPaciente = $conexion->prepare($sqlPaciente);

if (!$stmtPaciente) {
    die(" Error al preparar la consulta de pacientes: " . $conexion->error);
}

$stmtPaciente->bind_param("ss", $nombre, $telefono);

if ($stmtPaciente->execute()) {
    $id_paciente = $stmtPaciente->insert_id;
} else {
    die(" Error al registrar paciente: " . $stmtPaciente->error);
}

$stmtPaciente->close();

// 2. Obtener un doctor
$sqlDoctor = "SELECT Id_doctor FROM doctores LIMIT 1";
$resultDoctor = $conexion->query($sqlDoctor);
$id_doctor = ($resultDoctor && $resultDoctor->num_rows > 0) ? $resultDoctor->fetch_assoc()['Id_doctor'] : null;

// 3. Obtener un asistente
$sqlAsistente = "SELECT Id_asistentes FROM asistentes LIMIT 1";
$resultAsistente = $conexion->query($sqlAsistente);
$id_asistente = ($resultAsistente && $resultAsistente->num_rows > 0) ? $resultAsistente->fetch_assoc()['Id_asistentes'] : null;

if (!$id_doctor || !$id_asistente) {
    die("No hay doctor o asistente disponible.");
}

// 4. Insertar la cita
$sqlCita = "INSERT INTO citas (Nombre_paciente, Telefono, Fecha, Id_paciente, Id_doctor, Id_asistente)
            VALUES (?, ?, ?, ?, ?, ?)";
$stmtCita = $conexion->prepare($sqlCita);
$stmtCita->bind_param("sssiii", $nombre, $telefono, $fecha, $id_paciente, $id_doctor, $id_asistente);

if ($stmtCita->execute()) {
    echo " Cita registrada con éxito.";
} else {
    echo " Error al registrar la cita: " . $stmtCita->error;
}

$stmtCita->close();
$conexion->close();
?>
