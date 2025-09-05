<?php 

// Get all inventory items
function get_all_inventory_items($conn){
	$sql = "SELECT i.*, u.full_name as assigned_user FROM inventory i 
	        LEFT JOIN users u ON i.assigned_to = u.id 
	        ORDER BY i.created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$items = $stmt->fetchAll();
	}else $items = 0;

	return $items;
}

// Get inventory items by user ID
function get_inventory_by_user($conn, $user_id){
	$sql = "SELECT i.*, u.full_name as assigned_user FROM inventory i 
	        LEFT JOIN users u ON i.assigned_to = u.id 
	        WHERE i.assigned_to = ? ORDER BY i.created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$user_id]);

	if($stmt->rowCount() > 0){
		$items = $stmt->fetchAll();
	}else $items = 0;

	return $items;
}

// Get inventory items by status
function get_inventory_by_status($conn, $status){
	$sql = "SELECT i.*, u.full_name as assigned_user FROM inventory i 
	        LEFT JOIN users u ON i.assigned_to = u.id 
	        WHERE i.status = ? ORDER BY i.created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$status]);

	if($stmt->rowCount() > 0){
		$items = $stmt->fetchAll();
	}else $items = 0;

	return $items;
}

// Get inventory items by category
function get_inventory_by_category($conn, $category){
	$sql = "SELECT i.*, u.full_name as assigned_user FROM inventory i 
	        LEFT JOIN users u ON i.assigned_to = u.id 
	        WHERE i.category = ? ORDER BY i.created_at DESC";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$category]);

	if($stmt->rowCount() > 0){
		$items = $stmt->fetchAll();
	}else $items = 0;

	return $items;
}

// Insert new inventory item
function insert_inventory_item($conn, $data){
	$sql = "INSERT INTO inventory (item_name, category, brand, model, serial_number, assigned_to, status, purchase_date, purchase_price, warranty_expiry, description) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

// Update inventory item
function update_inventory_item($conn, $data){
	$sql = "UPDATE inventory SET item_name=?, category=?, brand=?, model=?, serial_number=?, assigned_to=?, status=?, purchase_date=?, purchase_price=?, warranty_expiry=?, description=? WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute($data);
}

// Delete inventory item
function delete_inventory_item($conn, $id){
	$sql = "DELETE FROM inventory WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
}

// Get inventory item by ID
function get_inventory_item_by_id($conn, $id){
	$sql = "SELECT i.*, u.full_name as assigned_user FROM inventory i 
	        LEFT JOIN users u ON i.assigned_to = u.id 
	        WHERE i.id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$item = $stmt->fetch();
	}else $item = 0;

	return $item;
}

// Count inventory items by status
function count_inventory_by_status($conn, $status){
	$sql = "SELECT COUNT(*) as count FROM inventory WHERE status = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$status]);
	
	$result = $stmt->fetch();
	return $result['count'];
}

// Count total inventory items
function count_total_inventory($conn){
	$sql = "SELECT COUNT(*) as count FROM inventory";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);
	
	$result = $stmt->fetch();
	return $result['count'];
}

// Get inventory summary
function get_inventory_summary($conn){
	$summary = array();
	
	$summary['total_items'] = count_total_inventory($conn);
	$summary['available'] = count_inventory_by_status($conn, 'available');
	$summary['assigned'] = count_inventory_by_status($conn, 'assigned');
	$summary['maintenance'] = count_inventory_by_status($conn, 'maintenance');
	$summary['damaged'] = count_inventory_by_status($conn, 'damaged');
	
	return $summary;
}

// Assign item to user
function assign_inventory_item($conn, $item_id, $user_id){
	$sql = "UPDATE inventory SET assigned_to=?, status='assigned' WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$user_id, $item_id]);
}

// Unassign item from user
function unassign_inventory_item($conn, $item_id){
	$sql = "UPDATE inventory SET assigned_to=NULL, status='available' WHERE id=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$item_id]);
}

// Get available items for assignment
function get_available_inventory_items($conn){
	$sql = "SELECT * FROM inventory WHERE status = 'available' ORDER BY category, item_name";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	if($stmt->rowCount() > 0){
		$items = $stmt->fetchAll();
	}else $items = 0;

	return $items;
}
