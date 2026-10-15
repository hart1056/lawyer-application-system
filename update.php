
<?php
require_once "dbconfig.php";

// Get the application ID from the table
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

// Find the application that needs to be edited
$stmt = $pdo->prepare("SELECT * FROM lawyer_applications WHERE application_id = ?");
$stmt->execute([$id]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application) {
    header("Location: index.php");
    exit;
}

// Save the changes after the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "UPDATE lawyer_applications SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            educational_background = :educational_background,
            law_school = :law_school,
            bar_admission_status = :bar_admission_status,
            legal_experience = :legal_experience,
            skills_specializations = :skills_specializations,
            position_applied = :position_applied
            WHERE application_id = :application_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":first_name" => trim($_POST["first_name"] ?? ""),
        ":last_name" => trim($_POST["last_name"] ?? ""),
        ":email" => trim($_POST["email"] ?? ""),
        ":phone" => trim($_POST["phone"] ?? ""),
        ":educational_background" => trim($_POST["educational_background"] ?? ""),
        ":law_school" => trim($_POST["law_school"] ?? ""),
        ":bar_admission_status" => trim($_POST["bar_admission_status"] ?? ""),
        ":legal_experience" => trim($_POST["legal_experience"] ?? ""),
        ":skills_specializations" => trim($_POST["skills_specializations"] ?? ""),
        ":position_applied" => trim($_POST["position_applied"] ?? ""),
        ":application_id" => $id
    ]);

    // Go back to the table after updating
    header("Location: index.php?message=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lawyer Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 25px;
        }

        .container {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 14px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button, a {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            background: #263b5e;
            color: white;
        }

        a {
            background: #ddd;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Application</h1>

    <form method="POST">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name"
               value="<?= htmlspecialchars($application["first_name"]) ?>" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name"
               value="<?= htmlspecialchars($application["last_name"]) ?>" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($application["email"]) ?>" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone"
               value="<?= htmlspecialchars($application["phone"] ?? "") ?>">

        <label for="educational_background">Educational Background</label>
        <input type="text" id="educational_background" name="educational_background"
               value="<?= htmlspecialchars($application["educational_background"] ?? "") ?>">

        <label for="law_school">Law School</label>
        <input type="text" id="law_school" name="law_school"
               value="<?= htmlspecialchars($application["law_school"] ?? "") ?>">

        <label for="bar_admission_status">Bar Admission Status</label>
        <select id="bar_admission_status" name="bar_admission_status">
            <option value="">Select status</option>
            <option value="Admitted" <?= ($application["bar_admission_status"] ?? "") === "Admitted" ? "selected" : "" ?>>Admitted</option>
            <option value="Not Yet Admitted" <?= ($application["bar_admission_status"] ?? "") === "Not Yet Admitted" ? "selected" : "" ?>>Not Yet Admitted</option>
        </select>

        <label for="legal_experience">Legal Experience</label>
        <textarea id="legal_experience" name="legal_experience" rows="3"><?= htmlspecialchars($application["legal_experience"] ?? "") ?></textarea>

        <label for="skills_specializations">Skills and Specializations</label>
        <textarea id="skills_specializations" name="skills_specializations" rows="3"><?= htmlspecialchars($application["skills_specializations"] ?? "") ?></textarea>

        <label for="position_applied">Position Applied For</label>
        <input type="text" id="position_applied" name="position_applied"
               value="<?= htmlspecialchars($application["position_applied"] ?? "") ?>" required>

        <button type="submit">Save Changes</button>
        <a href="index.php">Cancel</a>
    </form>
</div>
</body>
</html>
