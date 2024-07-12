<?php
// Datos de conexión
$serverName = "192.168.1.179\\SQLEXPRESS1G";
$connectionOptions = array(
    "Database" => "1G_HOSPITAL_ESP_TEST",
    "Uid" => "Test_1G",
    "PWD" => "t35t_2024"
);

try {
    // Establecer la conexión PDO
    $conexion = new PDO("sqlsrv:server=$serverName;Database=" . $connectionOptions['Database'], $connectionOptions['Uid'], $connectionOptions['PWD']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión establecida<br>";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$resultados = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el valor de empresa
    $emp = isset($_POST['empresa']) ? $_POST['empresa'] : '';
    echo "Valor de empresa: " . htmlspecialchars($emp) . "<br>"; // Depuración

    // Consulta SQL
    $sql = "SELECT * FROM [1G_HOSPITAL_ESP_TEST].[DBO].[VT_PROM_EXT_SIRE] WHERE EMPRESA = :empresa";

    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':empresa', $emp, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Número de resultados: " . count($resultados) . "<br>"; // Depuración
    } catch (PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultados de la Base de Datos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>Resultados de la Base de Datos</h1>
<table>
    <thead>
    <tr>
        <th>CLAVE DEL ARTÍCULO</th>
        <th>DESCRIPCIÓN DEL ARTÍCULO</th>
        <th>PRECIO</th>
        <th>PORCENTAJE DE DESCUENTO</th>
        <th>DESCUENTO</th>
        <th>IMPORTE CON DESCUENTO</th>
        <th>IVA</th>
        <th>TOTAL</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($resultados)): ?>
        <?php foreach ($resultados as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['CLAVEART']); ?></td>
                <td><?php echo htmlspecialchars($row['DESCRIBEAR']); ?></td>
                <td><?php echo htmlspecialchars($row['PRECIO']); ?></td>
                <td><?php echo htmlspecialchars($row['% DESCUENTO']); ?></td>
                <td><?php echo htmlspecialchars($row['DESCUENTO']); ?></td>
                <td><?php echo htmlspecialchars($row['IMPORTE CON DESCUENTO']); ?></td>
                <td><?php echo htmlspecialchars($row['IVA']); ?></td>
                <td><?php echo htmlspecialchars($row['TOTAL']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">No se encontraron resultados</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<p>Total de registros: <?php echo count($resultados); ?></p>
</body>
</html>
