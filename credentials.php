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
	<title>Employee Credentials | Digitazio Panel</title>
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
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Employee Credentials</h1>
					<div class="flex items-center space-x-2 text-orange-600 bg-orange-100 px-4 py-2 rounded-lg">
						<i class="fa fa-exclamation-triangle"></i>
						<span class="font-poppins text-body font-medium">Confidential Information</span>
					</div>
				</div>
				<p class="text-body font-poppins text-gray-600 mb-6">View employee login credentials for administrative purposes. Keep this information secure.</p>
			</div>

			<?php if ($users != 0) { ?>
			<div class="overflow-x-auto bg-white rounded-lg shadow-digitazio">
				<table class="w-full table-auto">
					<thead class="bg-atlantis text-white">
						<tr>
							<th class="px-6 py-4 text-left font-poppins font-semibold">#</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Full Name</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Username</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Password</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Role</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($users as $user) { ?>
						<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
							<td class="px-6 py-4 font-poppins text-body"><?=++$i?></td>
							<td class="px-6 py-4 font-poppins text-body font-medium"><?=$user['full_name']?></td>
							<td class="px-6 py-4 font-poppins text-body">
								<div class="flex items-center space-x-2">
									<span class="font-mono bg-gray-100 px-2 py-1 rounded"><?=$user['username']?></span>
									<button onclick="copyToClipboard('<?=$user['username']?>')" class="text-atlantis hover:text-atlantis-dark transition-colors duration-300" title="Copy Username">
										<i class="fa fa-copy"></i>
									</button>
								</div>
							</td>
							<td class="px-6 py-4 font-poppins text-body">
								<div class="flex items-center space-x-2">
									<span class="font-mono bg-gray-100 px-2 py-1 rounded password-field" data-password="<?=$user['password']?>">••••••••</span>
									<button onclick="togglePassword(this)" class="text-blue-500 hover:text-blue-600 transition-colors duration-300" title="Show/Hide Password">
										<i class="fa fa-eye"></i>
									</button>
									<button onclick="copyToClipboard('<?=$user['password']?>')" class="text-atlantis hover:text-atlantis-dark transition-colors duration-300" title="Copy Password">
										<i class="fa fa-copy"></i>
									</button>
								</div>
							</td>
							<td class="px-6 py-4">
								<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium bg-blue-100 text-blue-800">
									<?=ucfirst($user['role'])?>
								</span>
							</td>
							<td class="px-6 py-4">
								<div class="flex space-x-2">
									<a href="edit-user.php?id=<?=$user['id']?>" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-2 px-4 rounded transition-colors duration-300">
										<i class="fa fa-edit mr-1"></i>Edit
									</a>
									<button onclick="generatePassword(<?=$user['id']?>)" class="bg-green-500 hover:bg-green-600 text-white font-poppins text-xs py-2 px-4 rounded transition-colors duration-300">
										<i class="fa fa-refresh mr-1"></i>Reset Password
									</button>
								</div>
							</td>
						</tr>
					   <?php	} ?>
					</tbody>
				</table>
			</div>
		<?php }else { ?>
			<div class="text-center py-12">
				<i class="fa fa-users text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No employees found</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Add employees to view their credentials!</p>
				<a href="add-user.php" class="inline-block mt-4 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>Add Employee
				</a>
			</div>
		<?php  }?>
			
		</section>
	</div>

	<!-- Toast Notification -->
	<div id="toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 font-poppins text-body">
		<i class="fa fa-check mr-2"></i>
		<span id="toast-message">Copied to clipboard!</span>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(3)");
	active.classList.add("active");

	function copyToClipboard(text) {
		navigator.clipboard.writeText(text).then(function() {
			showToast('Copied to clipboard!');
		}).catch(function(err) {
			console.error('Could not copy text: ', err);
			showToast('Failed to copy', 'error');
		});
	}

	function togglePassword(button) {
		const passwordField = button.parentElement.querySelector('.password-field');
		const icon = button.querySelector('i');
		const actualPassword = passwordField.getAttribute('data-password');
		
		if (passwordField.textContent === '••••••••') {
			passwordField.textContent = actualPassword;
			icon.className = 'fa fa-eye-slash';
			button.title = 'Hide Password';
		} else {
			passwordField.textContent = '••••••••';
			icon.className = 'fa fa-eye';
			button.title = 'Show Password';
		}
	}

	function generatePassword(userId) {
		if (confirm('Are you sure you want to reset this employee\'s password? They will need to use the new password to login.')) {
			// Generate a random password
			const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			let newPassword = '';
			for (let i = 0; i < 8; i++) {
				newPassword += chars.charAt(Math.floor(Math.random() * chars.length));
			}
			
			// Here you would typically send an AJAX request to update the password
			// For now, we'll just show the new password
			showToast('New password generated: ' + newPassword + ' (Feature coming soon)');
		}
	}

	function showToast(message, type = 'success') {
		const toast = document.getElementById('toast');
		const toastMessage = document.getElementById('toast-message');
		
		toastMessage.textContent = message;
		
		if (type === 'error') {
			toast.className = toast.className.replace('bg-green-500', 'bg-red-500');
		} else {
			toast.className = toast.className.replace('bg-red-500', 'bg-green-500');
		}
		
		toast.classList.remove('translate-x-full');
		
		setTimeout(() => {
			toast.classList.add('translate-x-full');
		}, 3000);
	}
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>
