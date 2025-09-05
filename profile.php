<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/User.php";
    $user = get_user_by_id($conn, $_SESSION['id']);
    
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>My Profile | Digitazio Panel</title>
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
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">My Profile</h1>
					<a href="edit_profile.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-edit mr-2"></i>Edit Profile
					</a>
				</div>
			</div>

			<div class="max-w-2xl mx-auto">
				<div class="bg-white rounded-lg shadow-digitazio p-8">
					<div class="text-center mb-8">
						<div class="w-24 h-24 bg-atlantis rounded-full mx-auto mb-4 flex items-center justify-center">
							<i class="fa fa-user text-white text-3xl"></i>
						</div>
						<h2 class="text-subheading font-poppins font-semibold text-eerie-black"><?=$user['full_name']?></h2>
						<p class="text-body font-poppins text-gray-600">Employee</p>
					</div>

					<div class="space-y-4">
						<div class="border-b border-gray-200 pb-4">
							<div class="flex justify-between items-center">
								<span class="text-subheading-sm font-poppins font-medium text-eerie-black">Full Name</span>
								<span class="text-body font-poppins text-gray-700"><?=$user['full_name']?></span>
							</div>
						</div>
						<div class="border-b border-gray-200 pb-4">
							<div class="flex justify-between items-center">
								<span class="text-subheading-sm font-poppins font-medium text-eerie-black">Username</span>
								<span class="text-body font-poppins text-gray-700"><?=$user['username']?></span>
							</div>
						</div>
						<div class="border-b border-gray-200 pb-4">
							<div class="flex justify-between items-center">
								<span class="text-subheading-sm font-poppins font-medium text-eerie-black">Joined At</span>
								<span class="text-body font-poppins text-gray-700"><?=date('F j, Y', strtotime($user['created_at']))?></span>
							</div>
						</div>
						<div class="pt-4">
							<div class="flex justify-between items-center">
								<span class="text-subheading-sm font-poppins font-medium text-eerie-black">Role</span>
								<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium bg-blue-100 text-blue-800">Employee</span>
							</div>
						</div>
					</div>

					<div class="mt-8 text-center">
						<a href="edit_profile.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-8 rounded-lg transition-colors duration-300 shadow-digitazio">
							<i class="fa fa-edit mr-2"></i>Edit Profile
						</a>
					</div>
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