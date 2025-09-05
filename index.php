<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

	 include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Model/Request.php";
    include "app/Model/Inventory.php";
    include "app/Model/Accounting.php";
    include "app/Model/Report.php";

	if ($_SESSION['role'] == "admin") {
		  // Task statistics
		  $num_task = count_tasks($conn);
	     $pending_tasks = count_pending_tasks($conn);
	     $overdue_task = count_tasks_overdue($conn);
	     
	     // User statistics
	     $num_users = count_users($conn);
	     
	     // Request statistics
	     $request_counts = get_request_counts($conn);
	     
	     // Inventory statistics
	     $inventory_counts = [
	         'total' => 0,
	         'available' => 0,
	         'assigned' => 0,
	         'maintenance' => 0
	     ];
	     
	     // Accounting statistics
	     $financial_summary = get_financial_summary($conn);
	     
	     // Report statistics
	     $report_counts = get_report_counts($conn);
	     
	     // Recent activities
	     $recent_tasks = get_all_tasks($conn);
	     if ($recent_tasks != 0) {
	         $recent_tasks = array_slice($recent_tasks, 0, 5);
	     }
	     
	     $recent_requests = get_all_requests($conn);
	     if ($recent_requests != 0) {
	         $recent_requests = array_slice($recent_requests, 0, 5);
	     }
	     
	}else {
        // Employee task statistics
        $num_my_task = count_my_tasks($conn, $_SESSION['id']);
        $overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
        $pending = count_my_pending_tasks($conn, $_SESSION['id']);
	     $in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
	     $completed = count_my_completed_tasks($conn, $_SESSION['id']);
	     
	     // Employee requests
	     $my_requests = get_requests_by_employee($conn, $_SESSION['id']);
	     $pending_requests = 0;
	     if ($my_requests != 0) {
	         foreach ($my_requests as $req) {
	             if ($req['status'] == 'pending') $pending_requests++;
	         }
	         $my_requests = array_slice($my_requests, 0, 5);
	     }
	     
	     // Employee inventory
	     $my_inventory = [];
	     
	     // Recent tasks
	     $recent_tasks = get_my_tasks($conn, $_SESSION['id']);
	     if ($recent_tasks != 0) {
	         $recent_tasks = array_slice($recent_tasks, 0, 5);
	     }

	}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Digitazio Dashboard</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						'atlantis': '#8CC63F',
						'yellow': '#FFFF00',
						'eerie-black': '#1C1C1C',
						'atlantis-light': '#A5D65A',
						'atlantis-dark': '#6B9F2F',
					},
					fontFamily: {
						'poppins': ['Poppins', 'sans-serif'],
						'raleway': ['Raleway', 'sans-serif'],
					},
					fontSize: {
						'heading': ['44px', { lineHeight: '1.2', fontWeight: '600' }],
						'heading-sm': ['40px', { lineHeight: '1.2', fontWeight: '600' }],
						'subheading': ['18px', { lineHeight: '1.4', fontWeight: '400' }],
						'subheading-sm': ['16px', { lineHeight: '1.4', fontWeight: '400' }],
						'body': ['12px', { lineHeight: '1.5', fontWeight: '400' }],
						'body-sm': ['10px', { lineHeight: '1.5', fontWeight: '400' }],
					},
					boxShadow: {
						'digitazio': '0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)',
					}
				},
			},
		}
	</script>
	<link rel="stylesheet" href="css/style.css">
</head>
<body class="font-poppins bg-gray-50">
	<?php include "inc/header.php" ?>
	<div class="flex">
		<?php include "inc/nav.php" ?>
		<section class="flex-1 p-8 bg-white min-h-screen">
			<?php if ($_SESSION['role'] == "admin") { ?>
				<div class="mb-8">
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-2">Admin Dashboard</h1>
					<p class="text-subheading-sm text-gray-600 font-poppins">Daily operations overview</p>
				</div>

				<!-- Quick Stats Overview -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-atlantis">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-atlantis-light text-atlantis-dark">
								<i class="fa fa-users text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Total Employees</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$num_users?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-blue-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-blue-100 text-blue-600">
								<i class="fa fa-tasks text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Active Tasks</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$num_task?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-yellow-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
								<i class="fa fa-inbox text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Pending Requests</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$request_counts['pending']?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-red-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-red-100 text-red-600">
								<i class="fa fa-exclamation-triangle text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Overdue Tasks</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$overdue_task?></p>
							</div>
						</div>
					</div>
				</div>

				<!-- Daily Operations Sections -->
				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
					<!-- Task Management Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-tasks text-atlantis mr-2"></i>Task Management
							</h3>
							<a href="tasks.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View All</a>
						</div>
						<div class="space-y-3">
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Pending Tasks</span>
								<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-body-sm font-medium"><?=$pending_tasks?></span>
							</div>
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Overdue Tasks</span>
								<span class="bg-red-100 text-red-800 px-2 py-1 rounded text-body-sm font-medium"><?=$overdue_task?></span>
							</div>
						</div>
						<div class="mt-4 pt-4 border-t">
							<a href="create_task.php" class="bg-atlantis hover:bg-atlantis-dark text-white px-4 py-2 rounded text-body font-poppins transition-colors duration-300">
								<i class="fa fa-plus mr-1"></i>New Task
							</a>
						</div>
					</div>

					<!-- Employee Requests Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-inbox text-atlantis mr-2"></i>Employee Requests
							</h3>
							<a href="requests.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View All</a>
						</div>
						<div class="space-y-3">
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Pending Requests</span>
								<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-body-sm font-medium"><?=$request_counts['pending']?></span>
							</div>
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">In Review</span>
								<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-body-sm font-medium"><?=$request_counts['in_review']?></span>
							</div>
						</div>
						<?php if ($recent_requests != 0) { ?>
						<div class="mt-4 pt-4 border-t">
							<p class="text-body-sm font-poppins text-gray-600 mb-2">Recent Requests:</p>
							<?php foreach (array_slice($recent_requests, 0, 2) as $req) { ?>
							<div class="text-body-sm font-poppins text-gray-700 mb-1">• <?=substr($req['title'], 0, 30)?>...</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
					<!-- Inventory Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-cube text-atlantis mr-2"></i>Inventory
							</h3>
							<a href="inventory.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View</a>
						</div>
						<div class="space-y-2">
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Total Items</span>
								<span class="text-body font-poppins font-medium"><?=$inventory_counts['total']?></span>
							</div>
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Available</span>
								<span class="text-body font-poppins font-medium text-green-600"><?=$inventory_counts['available']?></span>
							</div>
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Assigned</span>
								<span class="text-body font-poppins font-medium text-blue-600"><?=$inventory_counts['assigned']?></span>
							</div>
						</div>
						<div class="mt-4">
							<a href="add-inventory.php" class="text-atlantis hover:text-atlantis-dark text-body-sm font-poppins">
								<i class="fa fa-plus mr-1"></i>Add Item
							</a>
						</div>
					</div>

					<!-- Reports Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-file-text text-atlantis mr-2"></i>Reports
							</h3>
							<a href="reports.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View</a>
						</div>
						<div class="space-y-2">
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Total Reports</span>
								<span class="text-body font-poppins font-medium"><?=$report_counts['total']?></span>
							</div>
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Completed</span>
								<span class="text-body font-poppins font-medium text-green-600"><?=$report_counts['completed']?></span>
							</div>
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Generating</span>
								<span class="text-body font-poppins font-medium text-yellow-600"><?=$report_counts['generating']?></span>
							</div>
						</div>
					</div>
				</div>
			<?php }else{ ?>
				<div class="mb-8">
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-2">Employee Dashboard</h1>
					<p class="text-subheading-sm text-gray-600 font-poppins">Daily operations overview</p>
				</div>

				<!-- Quick Stats Overview -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-atlantis">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-atlantis-light text-atlantis-dark">
								<i class="fa fa-tasks text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">My Tasks</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$num_my_task?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-yellow-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
								<i class="fa fa-clock-o text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Pending Tasks</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$pending?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-red-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-red-100 text-red-600">
								<i class="fa fa-exclamation-triangle text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Overdue Tasks</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$overdue_task?></p>
							</div>
						</div>
					</div>

					<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 border-green-500">
						<div class="flex items-center">
							<div class="p-3 rounded-full bg-green-100 text-green-600">
								<i class="fa fa-check text-xl"></i>
							</div>
							<div class="ml-4">
								<p class="text-body-sm font-poppins text-gray-500">Completed</p>
								<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$completed?></p>
							</div>
						</div>
					</div>
				</div>

				<!-- Daily Operations Sections -->
				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
					<!-- My Tasks Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-tasks text-atlantis mr-2"></i>My Tasks
							</h3>
							<a href="my_task.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View All</a>
						</div>
						<div class="space-y-3">
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">In Progress</span>
								<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-body-sm font-medium"><?=$in_progress?></span>
							</div>
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Overdue</span>
								<span class="bg-red-100 text-red-800 px-2 py-1 rounded text-body-sm font-medium"><?=$overdue_task?></span>
							</div>
						</div>
						<?php if ($recent_tasks != 0) { ?>
						<div class="mt-4 pt-4 border-t">
							<p class="text-body-sm font-poppins text-gray-600 mb-2">Recent Tasks:</p>
							<?php foreach (array_slice($recent_tasks, 0, 3) as $task) { ?>
							<div class="text-body-sm font-poppins text-gray-700 mb-1">• <?=substr($task['title'], 0, 35)?>...</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>

					<!-- My Requests Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-inbox text-atlantis mr-2"></i>My Requests
							</h3>
							<a href="my_requests.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View All</a>
						</div>
						<div class="space-y-3">
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Pending Requests</span>
								<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-body-sm font-medium"><?=$pending_requests?></span>
							</div>
							<div class="flex justify-between items-center p-3 bg-gray-50 rounded">
								<span class="text-body font-poppins">Total Requests</span>
								<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-body-sm font-medium"><?=($my_requests != 0) ? count($my_requests) : 0?></span>
							</div>
						</div>
						<div class="mt-4 pt-4 border-t">
							<a href="create_request.php" class="bg-atlantis hover:bg-atlantis-dark text-white px-4 py-2 rounded text-body font-poppins transition-colors duration-300">
								<i class="fa fa-plus mr-1"></i>New Request
							</a>
						</div>
					</div>
				</div>

				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
					<!-- My Inventory Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-cube text-atlantis mr-2"></i>My Inventory
							</h3>
							<a href="my_inventory.php" class="text-atlantis hover:text-atlantis-dark text-body font-poppins">View All</a>
						</div>
						<div class="space-y-2">
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Assigned Items</span>
								<span class="text-body font-poppins font-medium"><?=count($my_inventory)?></span>
							</div>
							<div class="flex justify-between">
								<span class="text-body-sm font-poppins text-gray-600">Status</span>
								<span class="text-body font-poppins font-medium text-green-600">Active</span>
							</div>
						</div>
						<div class="mt-4 pt-4 border-t">
							<p class="text-body-sm font-poppins text-gray-500">View your assigned equipment and resources</p>
						</div>
					</div>

					<!-- Quick Actions Section -->
					<div class="bg-white rounded-lg shadow-digitazio p-6">
						<div class="flex items-center justify-between mb-4">
							<h3 class="text-subheading font-poppins font-semibold text-eerie-black flex items-center">
								<i class="fa fa-bolt text-atlantis mr-2"></i>Quick Actions
							</h3>
						</div>
						<div class="space-y-3">
							<a href="edit_profile.php" class="flex items-center p-3 bg-gray-50 rounded hover:bg-gray-100 transition-colors duration-300">
								<i class="fa fa-user text-atlantis mr-3"></i>
								<span class="text-body font-poppins">Update Profile</span>
							</a>
							<a href="notifications.php" class="flex items-center p-3 bg-gray-50 rounded hover:bg-gray-100 transition-colors duration-300">
								<i class="fa fa-bell text-atlantis mr-3"></i>
								<span class="text-body font-poppins">View Notifications</span>
							</a>
							<a href="create_request.php" class="flex items-center p-3 bg-gray-50 rounded hover:bg-gray-100 transition-colors duration-300">
								<i class="fa fa-plus text-atlantis mr-3"></i>
								<span class="text-body font-poppins">Submit Request</span>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>
		</section>
	</div>

<script src="js/sidebar.js"></script>
<script type="text/javascript">
	// Legacy active class for compatibility
	var active = document.querySelector("#navList li:nth-child(1)");
	if (active) active.classList.add("active-nav");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>