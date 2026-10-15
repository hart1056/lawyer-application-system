
<?php
require_once "dbconfig.php";

// Delete the selected application
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "application_id", FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        $stmt = $pdo->prepare(
            "DELETE FROM lawyer_applications WHERE application_id = :id"
        );

        $stmt->execute([":id" => $id]);
    }

    // Return to the main page after deleting
    header("Location: index.php?message=deleted");
    exit;
}

header("Location: index.php");
exit;
?>
