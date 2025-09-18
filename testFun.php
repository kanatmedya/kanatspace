<?php
// Include the database connection
include "assets/db/db_connect.php";

// Fetch all projects
$sqlProjects = "SELECT id, employee FROM projects";
$resultProjects = $conn->query($sqlProjects);

if ($resultProjects->num_rows > 0) {
    while ($rowProject = $resultProjects->fetch_assoc()) {
        $projectID = $rowProject['id'];
        $employees = $rowProject['employee'];

        if (!empty($employees)) {
            // Split the employees by comma
            $employeeList = explode(",", $employees);

            foreach ($employeeList as $employeeName) {
                // Trim whitespace
                $employeeName = trim($employeeName);

                // Check if the employee exists in ac_users by full name
                $sqlUser = "SELECT id FROM ac_users WHERE CONCAT(name, ' ', surname) = ?";
                $stmtUser = $conn->prepare($sqlUser);
                $stmtUser->bind_param('s', $employeeName);
                $stmtUser->execute();
                $resultUser = $stmtUser->get_result();

                if ($resultUser->num_rows > 0) {
                    // If user exists, fetch their ID
                    $rowUser = $resultUser->fetch_assoc();
                    $userID = $rowUser['id'];

                    // Check if the user is already assigned to the project
                    $sqlCheckAssignee = "SELECT id FROM projects_assignees WHERE project_id = ? AND user_id = ?";
                    $stmtCheckAssignee = $conn->prepare($sqlCheckAssignee);
                    $stmtCheckAssignee->bind_param('ii', $projectID, $userID);
                    $stmtCheckAssignee->execute();
                    $resultCheckAssignee = $stmtCheckAssignee->get_result();

                    if ($resultCheckAssignee->num_rows == 0) {
                        // If not already assigned, insert into projects_assignees
                        $sqlInsertAssignee = "INSERT INTO projects_assignees (project_id, user_id) VALUES (?, ?)";
                        $stmtInsertAssignee = $conn->prepare($sqlInsertAssignee);
                        $stmtInsertAssignee->bind_param('ii', $projectID, $userID);

                        if ($stmtInsertAssignee->execute()) {
                            echo "Assigned $employeeName (UserID: $userID) to ProjectID: $projectID <br>";
                        } else {
                            echo "Failed to assign $employeeName to ProjectID: $projectID <br>";
                        }
                    } else {
                        echo "$employeeName (UserID: $userID) is already assigned to ProjectID: $projectID, skipping...<br>";
                    }
                } else {
                    // If no match is found in ac_users, skip
                    echo "User '$employeeName' not found in ac_users, skipping...<br>";
                }
            }
        }
    }
} else {
    echo "No projects found.";
}

// Close the database connection
$conn->close();
?>
