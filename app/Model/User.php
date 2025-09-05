<?php 

function get_all_users($conn){
	$sql = "SELECT * FROM users WHERE role =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute(["employee"]);

	if($stmt->rowCount() > 0){
		$users = $stmt->fetchAll();
	}else $users = 0;

	return $users;
}


function insert_user($conn, $data){
	$sql = "INSERT INTO users (full_name, username, password, role, job) VALUES(?,?,?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function update_user($conn, $data){
	$sql = "UPDATE users SET full_name=?, username=?, password=?, role=?, job=? WHERE id=? AND role=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function delete_user($conn, $data){
	// First, update tasks to remove the foreign key reference
	$update_sql = "UPDATE tasks SET assigned_to = NULL WHERE assigned_to = ?";
	$update_stmt = $conn->prepare($update_sql);
	$update_stmt->execute([$data[0]]);
	
	// Then delete the user
	$sql = "DELETE FROM users WHERE id=? AND role=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}


function get_user_by_id($conn, $id){
	$sql = "SELECT * FROM users WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$user = $stmt->fetch();
	}else $user = 0;

	return $user;
}

function update_profile($conn, $data){
	$sql = "UPDATE users SET full_name=?,  password=? WHERE id=? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

function count_users($conn){
	$sql = "SELECT id FROM users WHERE role='employee'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function check_username_exists($conn, $username, $exclude_id = null){
	if ($exclude_id) {
		$sql = "SELECT id FROM users WHERE username = ? AND id != ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$username, $exclude_id]);
	} else {
		$sql = "SELECT id FROM users WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$username]);
	}

	return $stmt->rowCount() > 0;
}