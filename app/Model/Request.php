<?php  

// Get all requests
function get_all_requests($conn) {
    $sql = "SELECT r.*, u.full_name as employee_name, a.full_name as admin_name 
            FROM requests r 
            LEFT JOIN users u ON r.employee_id = u.id 
            LEFT JOIN users a ON r.reviewed_by = a.id 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $requests = $stmt->fetchAll();
    } else {
        $requests = 0;
    }

    return $requests;
}

// Get requests by employee ID
function get_requests_by_employee($conn, $employee_id) {
    $sql = "SELECT r.*, a.full_name as admin_name 
            FROM requests r 
            LEFT JOIN users a ON r.reviewed_by = a.id 
            WHERE r.employee_id = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$employee_id]);

    if($stmt->rowCount() > 0){
        $requests = $stmt->fetchAll();
    } else {
        $requests = 0;
    }

    return $requests;
}

// Get request by ID
function get_request_by_id($conn, $id) {
    $sql = "SELECT r.*, u.full_name as employee_name, a.full_name as admin_name 
            FROM requests r 
            LEFT JOIN users u ON r.employee_id = u.id 
            LEFT JOIN users a ON r.reviewed_by = a.id 
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() == 1){
        $request = $stmt->fetch();
    } else {
        $request = 0;
    }

    return $request;
}

// Insert new request
function insert_request($conn, $data) {
    $sql = "INSERT INTO requests (employee_id, title, message, category, priority) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    if ($stmt) {
        return 1;
    } else {
        return 0;
    }
}

// Update request status and admin response
function update_request_status($conn, $data) {
    $sql = "UPDATE requests 
            SET status = ?, admin_response = ?, reviewed_by = ?, reviewed_at = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    if ($stmt) {
        return 1;
    } else {
        return 0;
    }
}

// Get request counts by status
function get_request_counts($conn) {
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = 'in_review' THEN 1 ELSE 0 END) as in_review
            FROM requests";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() == 1){
        $counts = $stmt->fetch();
    } else {
        $counts = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0, 'in_review' => 0];
    }

    return $counts;
}

// Get requests by status filter
function get_requests_by_status($conn, $status) {
    $sql = "SELECT r.*, u.full_name as employee_name, a.full_name as admin_name 
            FROM requests r 
            LEFT JOIN users u ON r.employee_id = u.id 
            LEFT JOIN users a ON r.reviewed_by = a.id 
            WHERE r.status = ? 
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$status]);

    if($stmt->rowCount() > 0){
        $requests = $stmt->fetchAll();
    } else {
        $requests = 0;
    }

    return $requests;
}

// Delete request
function delete_request($conn, $id) {
    $sql = "DELETE FROM requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt) {
        return 1;
    } else {
        return 0;
    }
}

?>
