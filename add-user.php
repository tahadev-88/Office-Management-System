<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
  
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Employee | Digitazio Panel</title>
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
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Add New Employee</h1>
					<a href="user.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-arrow-left mr-2"></i>Back to Employees
					</a>
				</div>
			</div>

			<div class="max-w-2xl mx-auto">
				<div class="bg-white rounded-lg shadow-digitazio p-8">
					<form method="POST" action="app/add-user.php" class="space-y-6">
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

						<div class="space-y-4">
							<div>
								<label class="block text-subheading font-poppins font-medium text-eerie-black mb-2">Full Name</label>
								<input type="text" 
									   name="full_name" 
									   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" 
									   placeholder="Enter full name"
									   required>
							</div>

							<div>
								<label class="block text-subheading font-poppins font-medium text-eerie-black mb-2">Username</label>
								<input type="text" 
									   name="user_name" 
									   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" 
									   placeholder="Enter username"
									   required>
							</div>

							<div>
								<label class="block text-subheading font-poppins font-medium text-eerie-black mb-2">Password</label>
								<input type="password" 
									   name="password" 
									   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" 
									   placeholder="Enter password"
									   required>
							</div>

							<div>
								<label class="block text-subheading font-poppins font-medium text-eerie-black mb-2">Job Title</label>
								<select name="job" 
									    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" 
									    required>
									<option value="">Select job title</option>
									<option value="Video Editor">Video Editor</option>
									<option value="Salesman">Salesman</option>
									<option value="Web Designer">Web Designer</option>
									<option value="Graphic Designer">Graphic Designer</option>
									<option value="Content Writer">Content Writer</option>
									<option value="Marketing Manager">Marketing Manager</option>
									<option value="Social Media Manager">Social Media Manager</option>
									<option value="Data Analyst">Data Analyst</option>
									<option value="Customer Support">Customer Support</option>
									<option value="Project Manager">Project Manager</option>
									<option value="Software Developer">Software Developer</option>
									<option value="HR Manager">HR Manager</option>
								</select>
							</div>

							<div class="flex space-x-4 pt-6">
								<button type="submit" class="flex-1 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
									<i class="fa fa-plus mr-2"></i>Add Employee
								</button>
								<a href="user.php" class="flex-1 text-center bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
									<i class="fa fa-times mr-2"></i>Cancel
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
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