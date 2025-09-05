<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
    	 header("Location: tasks.php");
    	 exit();
    }
   $users = get_all_users($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Task Status | Digitazio Panel</title>
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
				<div class="flex justify-between items-center mb-6">
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Update Task Status</h1>
					<a href="my_task.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-arrow-left mr-2"></i>Back to My Tasks
					</a>
				</div>
			</div>

			<div class="max-w-2xl mx-auto">
				<div class="bg-white rounded-lg shadow-digitazio p-8">
					<?php if (isset($_GET['error'])) {?>
					<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-poppins text-body">
						<i class="fa fa-exclamation-triangle mr-2"></i><?php echo stripcslashes($_GET['error']); ?>
					</div>
					<?php } ?>

					<?php if (isset($_GET['success'])) {?>
					<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
						<i class="fa fa-check-circle mr-2"></i><?php echo stripcslashes($_GET['success']); ?>
					</div>
					<?php } ?>

					<!-- Task Details -->
					<div class="mb-8">
						<h2 class="text-subheading font-poppins font-semibold text-eerie-black mb-6">Task Details</h2>
						<div class="space-y-4">
							<div class="bg-gray-50 p-4 rounded-lg">
								<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Title</label>
								<p class="text-body font-poppins text-gray-700"><?=$task['title']?></p>
							</div>
							<div class="bg-gray-50 p-4 rounded-lg">
								<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Description</label>
								<p class="text-body font-poppins text-gray-700"><?=$task['description']?></p>
							</div>
							<?php if($task['due_date'] != "") { ?>
							<div class="bg-gray-50 p-4 rounded-lg">
								<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Due Date</label>
								<p class="text-body font-poppins text-gray-700"><?=date('F j, Y', strtotime($task['due_date']))?></p>
							</div>
							<?php } ?>
						</div>
					</div>

					<!-- Status Update Form -->
					<div class="border-t border-gray-200 pt-8">
						<h3 class="text-subheading font-poppins font-semibold text-eerie-black mb-6">Update Status</h3>
						<form method="POST" action="app/update-task-employee.php" class="space-y-6">
							<div>
								<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-3">Current Status</label>
								<div class="space-y-3">
									<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-300 <?php echo ($task['status'] == 'pending') ? 'border-gray-400 bg-gray-50' : 'border-gray-200 hover:border-gray-300'; ?>">
										<input type="radio" name="status" value="pending" class="sr-only" <?php if($task['status'] == "pending") echo "checked"; ?>>
										<div class="flex items-center">
											<div class="w-4 h-4 rounded-full border-2 mr-3 <?php echo ($task['status'] == 'pending') ? 'border-gray-500 bg-gray-500' : 'border-gray-300'; ?>"></div>
											<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium bg-gray-100 text-gray-800 mr-3">Pending</span>
											<span class="text-body font-poppins text-gray-600">Task is waiting to be started</span>
										</div>
									</label>
									<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-300 <?php echo ($task['status'] == 'in_progress') ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:border-gray-300'; ?>">
										<input type="radio" name="status" value="in_progress" class="sr-only" <?php if($task['status'] == "in_progress") echo "checked"; ?>>
										<div class="flex items-center">
											<div class="w-4 h-4 rounded-full border-2 mr-3 <?php echo ($task['status'] == 'in_progress') ? 'border-blue-500 bg-blue-500' : 'border-gray-300'; ?>"></div>
											<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium bg-blue-100 text-blue-800 mr-3">In Progress</span>
											<span class="text-body font-poppins text-gray-600">Task is currently being worked on</span>
										</div>
									</label>
									<label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors duration-300 <?php echo ($task['status'] == 'completed') ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-gray-300'; ?>">
										<input type="radio" name="status" value="completed" class="sr-only" <?php if($task['status'] == "completed") echo "checked"; ?>>
										<div class="flex items-center">
											<div class="w-4 h-4 rounded-full border-2 mr-3 <?php echo ($task['status'] == 'completed') ? 'border-green-500 bg-green-500' : 'border-gray-300'; ?>"></div>
											<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium bg-green-100 text-green-800 mr-3">Completed</span>
											<span class="text-body font-poppins text-gray-600">Task has been finished</span>
										</div>
									</label>
								</div>
							</div>

							<input type="hidden" name="id" value="<?=$task['id']?>">

							<div class="flex space-x-4 pt-6">
								<button type="submit" class="flex-1 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
									<i class="fa fa-save mr-2"></i>Update Status
								</button>
								<a href="my_task.php" class="flex-1 text-center bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
									<i class="fa fa-times mr-2"></i>Cancel
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
	active.classList.add("active");

	// Enhanced radio button interaction
	document.addEventListener('DOMContentLoaded', function() {
		const radioInputs = document.querySelectorAll('input[type="radio"][name="status"]');
		const labels = document.querySelectorAll('label[for^="status"]');
		
		radioInputs.forEach(function(radio) {
			radio.addEventListener('change', function() {
				// Remove active styling from all labels
				document.querySelectorAll('label').forEach(function(label) {
					if (label.querySelector('input[name="status"]')) {
						label.classList.remove('border-atlantis', 'bg-atlantis', 'bg-opacity-10');
						label.classList.remove('border-green-400', 'bg-green-50');
						label.classList.remove('border-blue-400', 'bg-blue-50');
						label.classList.remove('border-gray-400', 'bg-gray-50');
						label.classList.add('border-gray-200');
						
						// Reset radio button styling
						const radioDiv = label.querySelector('.w-4.h-4');
						if (radioDiv) {
							radioDiv.classList.remove('border-atlantis', 'bg-atlantis');
							radioDiv.classList.remove('border-green-500', 'bg-green-500');
							radioDiv.classList.remove('border-blue-500', 'bg-blue-500');
							radioDiv.classList.remove('border-gray-500', 'bg-gray-500');
							radioDiv.classList.add('border-gray-300');
						}
					}
				});
				
				// Add active styling to selected label
				const selectedLabel = radio.closest('label');
				const radioDiv = selectedLabel.querySelector('.w-4.h-4');
				
				if (radio.value === 'pending') {
					selectedLabel.classList.add('border-gray-400', 'bg-gray-50');
					radioDiv.classList.add('border-gray-500', 'bg-gray-500');
				} else if (radio.value === 'in_progress') {
					selectedLabel.classList.add('border-blue-400', 'bg-blue-50');
					radioDiv.classList.add('border-blue-500', 'bg-blue-500');
				} else if (radio.value === 'completed') {
					selectedLabel.classList.add('border-green-400', 'bg-green-50');
					radioDiv.classList.add('border-green-500', 'bg-green-500');
				}
				
				selectedLabel.classList.remove('border-gray-200');
			});
		});
	});
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>