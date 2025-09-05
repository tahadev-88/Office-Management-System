<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    $text = "All Task";
    if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Due Today") {
    	$text = "Due Today";
      $tasks = get_all_tasks_due_today($conn);
      $num_task = count_tasks_due_today($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Overdue") {
    	$text = "Overdue";
      $tasks = get_all_tasks_overdue($conn);
      $num_task = count_tasks_overdue($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "No Deadline") {
    	$text = "No Deadline";
      $tasks = get_all_tasks_NoDeadline($conn);
      $num_task = count_tasks_NoDeadline($conn);

    }else{
    	 $tasks = get_all_tasks($conn);
       $num_task = count_tasks($conn);
    }
    $users = get_all_users($conn);
    

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>All Tasks | Digitazio Panel</title>
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-poppins bg-gray-50">
	<input type="checkbox" id="checkbox" class="hidden">
	<?php include "inc/header.php" ?>
	<div class="flex">
		<?php include "inc/nav.php" ?>
		<section class="flex-1 p-8 bg-white min-h-screen">
			<div class="mb-8">
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-4">Task Management</h1>
				<div class="flex flex-wrap gap-3 mb-6">
					<a href="create_task.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-plus mr-2"></i>Create Task
					</a>
					<a href="tasks.php?due_date=Due Today" class="bg-orange-500 hover:bg-orange-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Due Today
					</a>
					<a href="tasks.php?due_date=Overdue" class="bg-red-500 hover:bg-red-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Overdue
					</a>
					<a href="tasks.php?due_date=No Deadline" class="bg-yellow-500 hover:bg-yellow-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						No Deadline
					</a>
					<a href="tasks.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						All Tasks
					</a>
				</div>
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black"><?=$text?> (<?=$num_task?>)</h2>
			</div>

			<?php if (isset($_GET['success'])) {?>
      	  	<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
		<?php } ?>

			<?php if ($tasks != 0) { ?>
			<div class="overflow-x-auto bg-white rounded-lg shadow-digitazio">
				<table class="w-full table-auto">
					<thead class="bg-atlantis text-white">
						<tr>
							<th class="px-4 py-3 text-left font-poppins font-semibold">#</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Title</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Description</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Assigned To</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Due Date</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Status</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($tasks as $task) { ?>
						<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
							<td class="px-4 py-3 font-poppins text-body"><?=++$i?></td>
							<td class="px-4 py-3 font-poppins text-body font-medium"><?=$task['title']?></td>
							<td class="px-4 py-3 font-poppins text-body"><?=$task['description']?></td>
							<td class="px-4 py-3 font-poppins text-body">
								<?php 
		                  foreach ($users as $user) {
								if($user['id'] == $task['assigned_to']){
									echo $user['full_name'];
								}}?>
			            </td>
			            <td class="px-4 py-3 font-poppins text-body">
			            	<?php if($task['due_date'] == "") echo "No Deadline";
			                      else echo $task['due_date'];
			               ?>
			            </td>
			            <td class="px-4 py-3">
			            	<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
			            		<?php 
			            		if($task['status'] == 'completed') echo 'bg-green-100 text-green-800';
			            		elseif($task['status'] == 'in_progress') echo 'bg-blue-100 text-blue-800';
			            		else echo 'bg-gray-100 text-gray-800';
			            		?>">
			            		<?=$task['status']?>
			            	</span>
			            </td>
							<td class="px-4 py-3">
								<div class="flex space-x-2">
									<a href="edit-task.php?id=<?=$task['id']?>" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300">
										Edit
									</a>
									<a href="delete-task.php?id=<?=$task['id']?>" class="bg-red-500 hover:bg-red-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300">
										Delete
									</a>
								</div>
							</td>
						</tr>
					   <?php	} ?>
					</tbody>
				</table>
			</div>
		<?php }else { ?>
			<div class="text-center py-12">
				<i class="fa fa-tasks text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No tasks found</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Create your first task to get started!</p>
			</div>
		<?php  }?>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>