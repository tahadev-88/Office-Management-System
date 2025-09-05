<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";

    $users = get_all_users($conn);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Task | Digitazio Panel</title>
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
			<div class="max-w-2xl">
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-8">Create New Task</h1>
				
				<div class="bg-white p-8 rounded-lg shadow-digitazio">
					<form method="POST" action="app/add-task.php" class="space-y-6">
						<?php if (isset($_GET['error'])) {?>
							<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded font-poppins text-body">
								<?php echo stripcslashes($_GET['error']); ?>
							</div>
						<?php } ?>

						<?php if (isset($_GET['success'])) {?>
							<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded font-poppins text-body">
								<?php echo stripcslashes($_GET['success']); ?>
							</div>
						<?php } ?>

						<div class="space-y-2">
							<label for="title" class="block text-body font-poppins font-medium text-eerie-black">Task Title</label>
							<input type="text" 
								   name="title" 
								   id="title"
								   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body" 
								   placeholder="Enter task title"
								   required>
						</div>

						<div class="space-y-2">
							<label for="description" class="block text-body font-poppins font-medium text-eerie-black">Description</label>
							<textarea name="description" 
									  id="description"
									  rows="4"
									  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body resize-vertical" 
									  placeholder="Enter task description"></textarea>
						</div>

						<div class="space-y-2">
							<label for="due_date" class="block text-body font-poppins font-medium text-eerie-black">Due Date</label>
							<input type="date" 
								   name="due_date" 
								   id="due_date"
								   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body">
						</div>

						<div class="space-y-2">
							<label for="assigned_to" class="block text-body font-poppins font-medium text-eerie-black">Assign To</label>
							<select name="assigned_to" 
									id="assigned_to"
									class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body"
									required>
								<option value="0">Select employee</option>
								<?php if ($users !=0) { 
									foreach ($users as $user) {
								?>
								<option value="<?=$user['id']?>"><?=$user['full_name']?></option>
								<?php } } ?>
							</select>
						</div>

						<div class="flex gap-4 pt-4">
							<button type="submit" 
									class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-8 rounded-lg transition-colors duration-300 shadow-digitazio">
								<i class="fa fa-plus mr-2"></i>Create Task
							</button>
							<a href="tasks.php" 
							   class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-8 rounded-lg transition-colors duration-300">
								<i class="fa fa-arrow-left mr-2"></i>Back to Tasks
							</a>
						</div>
					</form>
				</div>
			</div>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(3)");
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