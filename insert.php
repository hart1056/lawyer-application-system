
<?php
require_once "dbconfig.php";

// Save the application details from the form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "INSERT INTO lawyer_applications
            (first_name, last_name, email, phone, educational_background,
             law_school, bar_admission_status, legal_experience,
             skills_specializations, position_applied)
            VALUES
            (:first_name, :last_name, :email, :phone, :educational_background,
             :law_school, :bar_admission_status, :legal_experience,
             :skills_specializations, :position_applied)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":first_name" => trim($_POST["first_name"]),
        ":last_name" => trim($_POST["last_name"]),
        ":email" => trim($_POST["email"]),
        ":phone" => trim($_POST["phone"] ?? ""),
        ":educational_background" => trim($_POST["educational_background"] ?? ""),
        ":law_school" => trim($_POST["law_school"] ?? ""),
        ":bar_admission_status" => trim($_POST["bar_admission_status"] ?? ""),
        ":legal_experience" => trim($_POST["legal_experience"] ?? ""),
        ":skills_specializations" => trim($_POST["skills_specializations"] ?? ""),
        ":position_applied" => trim($_POST["position_applied"])
    ]);

    // Return to the main page after saving
    header("Location: index.php?message=inserted");
    exit;
}

header("Location: index.php");
exit;
?>
