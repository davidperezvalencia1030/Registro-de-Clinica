<?php
$conexion = new mysqli("localhost", "root", "", "clinica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (isset($_POST["Registrar"])) {
    $idPaciente = $_POST["Id_paciente"];
    $diagnostico = $_POST["Diagnostico"];
    $descripcion = $_POST["Descripcion"];
    $Nom_medicamento = $_POST["Nom_medicamento"];
    $dosis = $_POST["Dosis"];

    // Verificar si el paciente existe
    $stmtPaciente = $conexion->prepare("SELECT Id_paciente FROM pacientes WHERE Id_paciente = ?");
    $stmtPaciente->bind_param("i", $idPaciente);
    $stmtPaciente->execute();
    $resultPaciente = $stmtPaciente->get_result();

    if ($resultPaciente->num_rows == 0) {
        echo "El paciente con ID $idPaciente no existe.";
        exit;
    }

    // Obtener el primer doctor disponible
    $sqlDoctor = "SELECT Id_doctor FROM doctores LIMIT 1";
    $resultDoctor = $conexion->query($sqlDoctor);

    if ($resultDoctor->num_rows > 0) {
        $rowDoctor = $resultDoctor->fetch_assoc();
        $idDoctor = $rowDoctor["Id_doctor"];
    } else {
        echo "No hay doctores registrados.";
        exit;
    }

    // Insertar tratamiento
    $stmtInsert = $conexion->prepare("INSERT INTO tratamientos (Diagnostico, Descripcion, Nom_medicamento, Dosis, Id_doctor, Id_paciente) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssii", $diagnostico, $descripcion, $Nom_medicamento, $dosis, $idDoctor, $idPaciente);

    if ($stmtInsert->execute()) {
        echo "Tratamiento registrado correctamente.";
    } else {
        echo "Error al registrar el tratamiento: " . $stmtInsert->error;
    }

    $stmtInsert->close();
    $stmtPaciente->close();
    $conexion->close();
}
?>
