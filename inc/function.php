<?php
require 'connection.php';

function afficherDepartements(mysqli $db) {
    $query = "
        SELECT d.dept_no, d.dept_name, e.first_name, e.last_name
        FROM departments d
        LEFT JOIN dept_manager dm ON d.dept_no = dm.dept_no 
        LEFT JOIN employees e ON dm.emp_no = e.emp_no
        WHERE dm.to_date > NOW()
        ORDER BY d.dept_name
    ";
    
    $result = mysqli_query($db, $query);

    if (!$result) {
        echo "<p class='text-danger'>Erreur de chargement des départements.</p>";
        return;
    }

    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped align-middle">';
    echo '<thead class="table-light">
            <tr>
                <th>Numéro</th>
                <th>Nom du département</th>
                <th>Manager actuel</th>
                <th>Actions</th>
            </tr>
          </thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        $manager = trim($row['first_name'] . ' ' . $row['last_name']);
        if ($manager === '') $manager = 'Non défini';

        echo '<tr>';
        echo '<td>' . $row['dept_no'] . '</td>';
        echo '<td>' . $row['dept_name'] . '</td>';
        echo '<td>' . $manager . '</td>';
        echo '<td>
                <form action="pages/employes_departement.php" method="get" class="d-inline">
                    <input type="hidden" name="dept_no" value="' . $row['dept_no'] . '">
                    <button type="submit" class="btn btn-outline-primary btn-sm">Voir les employés</button>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
function getEmployesParDepartement(mysqli $db, string $dept_no, int $page = 0): array {
    $offset = $page * 20;

    $dept_query = "SELECT dept_name FROM departments WHERE dept_no = '$dept_no'";
    $dept_result = mysqli_query($db, $dept_query);
    $dept_info = mysqli_fetch_assoc($dept_result);

    $employees_query = "
        SELECT e.emp_no, e.first_name, e.last_name, e.hire_date
        FROM employees e
        JOIN dept_emp de ON e.emp_no = de.emp_no
        WHERE de.dept_no = '$dept_no' AND de.to_date > NOW()
        ORDER BY e.last_name, e.first_name
        LIMIT $offset, 20
    ";
    $employees_result = mysqli_query($db, $employees_query);
    
    $employees = [];
    while ($row = mysqli_fetch_assoc($employees_result)) {
        $employees[] = $row;
    }


    $count_query = "
        SELECT COUNT(*) as total 
        FROM employees e
        JOIN dept_emp de ON e.emp_no = de.emp_no
        WHERE de.dept_no = '$dept_no' AND de.to_date > NOW()
    ";
    $count_result = mysqli_query($db, $count_query);
    $total = mysqli_fetch_assoc($count_result)['total'];

    return [
        'dept_name' => $dept_info['dept_name'] ?? 'Inconnu',
        'employees' => $employees,
        'total' => $total
    ];
}

?>