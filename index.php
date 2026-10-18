
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "dbconfig.php";

$stmt = $pdo->query(
    "SELECT * FROM lawyer_applications ORDER BY date_added DESC"
);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

function e($value) {
    return htmlspecialchars((string)($value ?? ""), ENT_QUOTES, "UTF-8");
}
?>

// i love designing so i decided to design it with purple themed:>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lawyer Application System</title>

<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f2f8;
    color: #33283b;
}

header {
    background: #4b315f;
    color: white;
    padding: 25px 7%;
}

header h1 { margin: 0 0 8px; }
header p { margin: 0; color: #e5d9ed; }

main {
    max-width: 1250px;
    margin: 30px auto;
    padding: 0 20px;
}

section {
    background: white;
    padding: 24px;
    margin-bottom: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 12px #33283b12;
}

h2 { color: #4b315f; margin-top: 0; }

form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.field { display: flex; flex-direction: column; gap: 7px; }
.full { grid-column: 1 / -1; }

input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #d8cce0;
    border-radius: 5px;
    font: inherit;
}

textarea { min-height: 85px; resize: vertical; }

button, .edit {
    display: inline-block;
    padding: 9px 14px;
    border: 0;
    border-radius: 5px;
    background: #68477d;
    color: white;
    text-decoration: none;
    cursor: pointer;
    font: inherit;
}

button:hover, .edit:hover { background: #4b315f; }

.delete { background: #a34459; }
.delete:hover { background: #813044; }

.actions { display: flex; gap: 7px; align-items: center; }
.actions form { display: block; }

.table-wrap { overflow-x: auto; }

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

th, td {
    padding: 11px;
    border-bottom: 1px solid #e8e0ed;
    text-align: left;
    vertical-align: top;
}

th { background: #eee6f3; color: #4b315f; }
tr:hover td { background: #faf8fc; }

.message {
    padding: 12px;
    margin-bottom: 20px;
    background: #eee6f3;
    border-radius: 5px;
}

@media (max-width: 650px) {
    form { grid-template-columns: 1fr; }
    .full { grid-column: auto; }
}
</style>
</head>

<body>
<header>
    <h1>Lawyer Application System</h1>
    <p>Manage and review submitted job applications.</p>
</header>

<main>

<?php if (isset($_GET["message"])): ?>
    <div class="message">
        <?php
        $messages = [
            "inserted" => "Application submitted successfully.",
            "updated" => "Application updated successfully.",
            "deleted" => "Application deleted successfully."
        ];
        echo e($messages[$_GET["message"]] ?? "");
        ?>
    </div>
<?php endif; ?>

<section>
    <h2>Job Application Form</h2>

    <form action="insert.php" method="POST">
        <div class="field">
            <label for="first_name">First Name *</label>
            <input id="first_name" name="first_name" required>
        </div>

        <div class="field">
            <label for="last_name">Last Name *</label>
            <input id="last_name" name="last_name" required>
        </div>

        <div class="field">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="field">
            <label for="phone">Phone Number</label>
            <input id="phone" name="phone">
        </div>

        <div class="field">
            <label for="educational_background">Educational Background</label>
            <input id="educational_background" name="educational_background">
        </div>

        <div class="field">
            <label for="law_school">Law School</label>
            <input id="law_school" name="law_school">
        </div>

        <div class="field">
            <label for="bar_admission_status">Bar Admission Status</label>
            <select id="bar_admission_status" name="bar_admission_status">
                <option value="">Select status</option>
                <option value="Admitted">Admitted</option>
                <option value="Not Yet Admitted">Not Yet Admitted</option>
            </select>
        </div>

        <div class="field">
            <label for="position_applied">Position Applied For</label>
            <input id="position_applied" name="position_applied"
                   value="Lawyer" required>
        </div>

        <div class="field full">
            <label for="legal_experience">Legal Experience</label>
            <textarea id="legal_experience" name="legal_experience"></textarea>
        </div>

        <div class="field full">
            <label for="skills_specializations">Skills and Specializations</label>
            <textarea id="skills_specializations"
                      name="skills_specializations"></textarea>
        </div>

        <div class="full">
            <button type="submit">Submit Application</button>
        </div>
    </form>
</section>

<section>
    <h2>Submitted Applications</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Education</th>
                    <th>Law School</th>
                    <th>Bar Status</th>
                    <th>Legal Experience</th>
                    <th>Skills</th>
                    <th>Position</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($applications): ?>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= e($app["application_id"]) ?></td>
                    <td><?= e($app["first_name"] . " " . $app["last_name"]) ?></td>
                    <td><?= e($app["email"]) ?></td>
                    <td><?= e($app["phone"]) ?></td>
                    <td><?= e($app["educational_background"]) ?></td>
                    <td><?= e($app["law_school"]) ?></td>
                    <td><?= e($app["bar_admission_status"]) ?></td>
                    <td><?= e($app["legal_experience"]) ?></td>
                    <td><?= e($app["skills_specializations"]) ?></td>
                    <td><?= e($app["position_applied"]) ?></td>
                    <td><?= e($app["date_added"]) ?></td>
                    <td>
                        <div class="actions">
                            <a class="edit"
                               href="update.php?id=<?= e($app["application_id"]) ?>">
                                Edit
                            </a>

                            <form action="delete.php" method="POST"
                                  onsubmit="return confirm('Delete this application?');">
                                <input type="hidden" name="application_id"
                                       value="<?= e($app["application_id"]) ?>">
                                <button class="delete" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">No applications submitted yet.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

</main>
</body>
</html>
