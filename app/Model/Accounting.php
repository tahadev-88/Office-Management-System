<?php 

// Get all accounting entries
function get_all_accounting_entries($conn){
	$sql = "SELECT * FROM accounting ORDER BY created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$entries = $stmt->fetchAll();
	}else $entries = 0;

	return $entries;
}

// Get accounting entries by category
function get_accounting_entries_by_category($conn, $category){
	$sql = "SELECT * FROM accounting WHERE category = ? ORDER BY created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$category]);

	if($stmt->rowCount() > 0){
		$entries = $stmt->fetchAll();
	}else $entries = 0;

	return $entries;
}

// Insert new accounting entry
function insert_accounting_entry($conn, $data){
	$sql = "INSERT INTO accounting (category, type, description, amount, date, created_at) VALUES(?,?,?,?,?,NOW())";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

// Update accounting entry
function update_accounting_entry($conn, $data){
	$sql = "UPDATE accounting SET category=?, type=?, description=?, amount=?, date=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

// Delete accounting entry
function delete_accounting_entry($conn, $id){
	$sql = "DELETE FROM accounting WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
}

// Get accounting entry by ID
function get_accounting_entry_by_id($conn, $id){
	$sql = "SELECT * FROM accounting WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$entry = $stmt->fetch();
	}else $entry = 0;

	return $entry;
}

// Get total by category and type
function get_total_by_category_type($conn, $category, $type = null){
	if($type){
		$sql = "SELECT SUM(amount) as total FROM accounting WHERE category = ? AND type = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$category, $type]);
	} else {
		$sql = "SELECT SUM(amount) as total FROM accounting WHERE category = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$category]);
	}

	$result = $stmt->fetch();
	return $result['total'] ? $result['total'] : 0;
}

// Get financial summary
function get_financial_summary($conn){
	$summary = array();
	
	// Assets
	$summary['total_assets'] = get_total_by_category_type($conn, 'assets');
	
	// Revenue
	$summary['total_revenue'] = get_total_by_category_type($conn, 'revenue');
	
	// Expenses (including salaries)
	$summary['total_expenses'] = get_total_by_category_type($conn, 'expenses');
	$summary['total_salaries'] = get_total_by_category_type($conn, 'salaries');
	
	// Shares
	$summary['total_shares'] = get_total_by_category_type($conn, 'shares');
	
	// Calculate profit/loss
	$summary['profit_loss'] = $summary['total_revenue'] - ($summary['total_expenses'] + $summary['total_salaries']);
	
	return $summary;
}

// Count entries by category
function count_entries_by_category($conn, $category){
	$sql = "SELECT COUNT(*) as count FROM accounting WHERE category = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$category]);
	
	$result = $stmt->fetch();
	return $result['count'];
}

// Get recent entries
function get_recent_accounting_entries($conn, $limit = 10){
	$sql = "SELECT * FROM accounting ORDER BY created_at DESC LIMIT ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$limit]);

	if($stmt->rowCount() > 0){
		$entries = $stmt->fetchAll();
	}else $entries = 0;

	return $entries;
}
