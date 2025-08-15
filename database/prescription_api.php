<?php
header('Content-Type: application/json');

include 'db_connect.php';

$response = ['success' => false, 'message' => ''];

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get':
        $sql = "SELECT * FROM prescriptions";
        $result = $conn->query($sql);

        $prescriptions = [];

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $prescriptions[] = $row;
            }
        }
        echo json_encode($prescriptions);
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $patient_id = $data['patient_id'] ?? '';
            $doctor_id = $data['doctor_id'] ?? '';
            $medication = $data['medication'] ?? '';
            $dosage = $data['dosage'] ?? '';
            $instructions = $data['instructions'] ?? '';
            $date_prescribed = date('Y-m-d H:i:s');

            if (empty($patient_id) || empty($doctor_id) || empty($medication) || empty($dosage) || empty($instructions)) {
                $response['message'] = 'All fields are required.';
            } else {
                $stmt = $conn->prepare("INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, instructions, date_prescribed) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissss", $patient_id, $doctor_id, $medication, $dosage, $instructions, $date_prescribed);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Prescription added successfully.';
                } else {
                    $response['message'] = 'Error adding prescription: ' . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            $response['message'] = 'Invalid request method.';
        }
        echo json_encode($response);
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $prescription_id = $data['prescription_id'] ?? '';
            $patient_id = $data['patient_id'] ?? '';
            $doctor_id = $data['doctor_id'] ?? '';
            $medication = $data['medication'] ?? '';
            $dosage = $data['dosage'] ?? '';
            $instructions = $data['instructions'] ?? '';

            if (empty($prescription_id) || empty($patient_id) || empty($doctor_id) || empty($medication) || empty($dosage) || empty($instructions)) {
                $response['message'] = 'All fields are required.';
            } else {
                $stmt = $conn->prepare("UPDATE prescriptions SET patient_id = ?, doctor_id = ?, medication = ?, dosage = ?, instructions = ? WHERE id = ?");
                $stmt->bind_param("iisssi", $patient_id, $doctor_id, $medication, $dosage, $instructions, $prescription_id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Prescription updated successfully.';
                    } else {
                        $response['message'] = 'No changes made or prescription not found.';
                    }
                } else {
                    $response['message'] = 'Error updating prescription: ' . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            $response['message'] = 'Invalid request method.';
        }
        echo json_encode($response);
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $prescription_id = $data['prescription_id'] ?? '';

            if (empty($prescription_id)) {
                $response['message'] = 'Prescription ID is required.';
            } else {
                $stmt = $conn->prepare("DELETE FROM prescriptions WHERE id = ?");
                $stmt->bind_param("i", $prescription_id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Prescription deleted successfully.';
                    } else {
                        $response['message'] = 'Prescription not found.';
                    }
                } else {
                    $response['message'] = 'Error deleting prescription: ' . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            $response['message'] = 'Invalid request method.';
        }
        echo json_encode($response);
        break;

    default:
        $response['message'] = 'Invalid action specified.';
        echo json_encode($response);
        break;
}

$conn->close();
?>