<?php  

// Get all reports
function get_all_reports($conn) {
    $sql = "SELECT r.*, u.full_name as generated_by_name 
            FROM reports r 
            LEFT JOIN users u ON r.generated_by = u.id 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $reports = $stmt->fetchAll();
    } else {
        $reports = 0;
    }

    return $reports;
}

// Get reports by type
function get_reports_by_type($conn, $type) {
    $sql = "SELECT r.*, u.full_name as generated_by_name 
            FROM reports r 
            LEFT JOIN users u ON r.generated_by = u.id 
            WHERE r.type = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$type]);

    if($stmt->rowCount() > 0){
        $reports = $stmt->fetchAll();
    } else {
        $reports = 0;
    }

    return $reports;
}

// Get report by ID
function get_report_by_id($conn, $id) {
    $sql = "SELECT r.*, u.full_name as generated_by_name 
            FROM reports r 
            LEFT JOIN users u ON r.generated_by = u.id 
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() == 1){
        $report = $stmt->fetch();
    } else {
        $report = 0;
    }

    return $report;
}

// Insert new report
function insert_report($conn, $data) {
    $sql = "INSERT INTO reports (title, type, period, year, month, file_path, file_size, generated_by, status, data_summary) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    if ($stmt) {
        return $conn->lastInsertId();
    } else {
        return 0;
    }
}

// Update report status
function update_report_status($conn, $id, $status, $file_path = null, $file_size = null) {
    if ($file_path && $file_size) {
        $sql = "UPDATE reports SET status = ?, file_path = ?, file_size = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status, $file_path, $file_size, $id]);
    } else {
        $sql = "UPDATE reports SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$status, $id]);
    }

    if ($stmt) {
        return 1;
    } else {
        return 0;
    }
}

// Get report counts by type
function get_report_counts($conn) {
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN type = 'employee_month' THEN 1 ELSE 0 END) as employee_month,
                SUM(CASE WHEN type = 'employee_year' THEN 1 ELSE 0 END) as employee_year,
                SUM(CASE WHEN type = 'accounting' THEN 1 ELSE 0 END) as accounting,
                SUM(CASE WHEN type = 'tasks' THEN 1 ELSE 0 END) as tasks,
                SUM(CASE WHEN type = 'sales' THEN 1 ELSE 0 END) as sales,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'generating' THEN 1 ELSE 0 END) as generating,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
            FROM reports";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() == 1){
        $counts = $stmt->fetch();
    } else {
        $counts = ['total' => 0, 'employee_month' => 0, 'employee_year' => 0, 'accounting' => 0, 'tasks' => 0, 'sales' => 0, 'completed' => 0, 'generating' => 0, 'failed' => 0];
    }

    return $counts;
}

// Delete report
function delete_report($conn, $id) {
    $sql = "DELETE FROM reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt) {
        return 1;
    } else {
        return 0;
    }
}

// Get employee performance data for reports
function get_employee_performance($conn, $year, $month = null) {
    if ($month) {
        $sql = "SELECT u.id, u.full_name, 
                       COUNT(t.id) as total_tasks,
                       SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                       ROUND((SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 2) as completion_rate
                FROM users u 
                LEFT JOIN tasks t ON u.id = t.assigned_to 
                WHERE u.role = 'employee' 
                AND YEAR(t.created_at) = ? AND MONTH(t.created_at) = ?
                GROUP BY u.id, u.full_name 
                ORDER BY completion_rate DESC, completed_tasks DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year, $month]);
    } else {
        $sql = "SELECT u.id, u.full_name, 
                       COUNT(t.id) as total_tasks,
                       SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                       ROUND((SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 2) as completion_rate
                FROM users u 
                LEFT JOIN tasks t ON u.id = t.assigned_to 
                WHERE u.role = 'employee' 
                AND YEAR(t.created_at) = ?
                GROUP BY u.id, u.full_name 
                ORDER BY completion_rate DESC, completed_tasks DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year]);
    }

    if($stmt->rowCount() > 0){
        $performance = $stmt->fetchAll();
    } else {
        $performance = 0;
    }

    return $performance;
}

// Get tasks summary for reports
function get_tasks_summary($conn, $year, $month = null) {
    if ($month) {
        $sql = "SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks
                FROM tasks 
                WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year, $month]);
    } else {
        $sql = "SELECT 
                    COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks
                FROM tasks 
                WHERE YEAR(created_at) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$year]);
    }

    if($stmt->rowCount() == 1){
        $summary = $stmt->fetch();
    } else {
        $summary = ['total_tasks' => 0, 'completed_tasks' => 0, 'in_progress_tasks' => 0, 'pending_tasks' => 0];
    }

    return $summary;
}

?>
