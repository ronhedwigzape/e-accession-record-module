<?php
require_once '_init.php';

// get authenticated user
$authUser = getUser();

if(!$authUser)
    denyAccess();

else if($authUser['userType'] !== 'admin')
    denyAccess();

else {
    require_once 'models/Admin.php';
    require_once 'models/Accession.php';
    $admin = new Admin($authUser['username'], $_SESSION['pass']);

    if(!$admin->authenticated())
        denyAccess();
    else {

        if (isset($_GET['load'])) {
            // Fetch all accession records
            echo json_encode(Accession::rows());
        }

        else if (isset($_POST['save'])) {
            // Create or update an accession record
            $data = json_decode($_POST['save'], true);
            if ($data) {
                $accession = new Accession($data);
                if ($accession->save()) {
                    echo json_encode(['message' => 'Accession record saved successfully.']);
                } else {
                    App::returnError('HTTP/1.1 500', 'Failed to save accession record.');
                }
            } else {
                App::returnError('HTTP/1.1 400', 'Invalid input data.');
            }
        }

        else if (isset($_POST['delete'])) {
            // Delete an existing accession record
            $id = $_POST['delete'];
            $accession = Accession::findById($id);
            if ($accession) {
                if ($accession->destroy()) {
                    echo json_encode(['message' => 'Accession record deleted successfully.']);
                } else {
                    App::returnError('HTTP/1.1 500', 'Failed to delete accession record.');
                }
            } else {
                App::returnError('HTTP/1.1 404', 'Accession record not found.');
            }
        }

        else if (isset($_GET['generateReport'])) {
            $type = isset($_GET['type']) ? $_GET['type'] : null;
            $department = isset($_GET['department']) ? $_GET['department'] : null;
            $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

            try {
                Accession::generateReport($type, $department, $start_date, $end_date);
            } catch (Exception $e) {
                App::returnError('HTTP/1.1 500', 'Error generating report: ' . $e->getMessage());
            }
        }

        else {
            denyAccess();
        }
    }
}
?>
