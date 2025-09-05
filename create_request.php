<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Request | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-4">
					Create New Request
					<a href="my_requests.php" class="text-atlantis hover:text-atlantis-dark transition-colors duration-300 ml-2">
						<i class="fa fa-arrow-left mr-2"></i>Back to My Requests
					</a>
				</h1>
				<p class="text-subheading-sm text-gray-600 font-poppins">Submit a request to the admin for approval</p>
			</div>

			<?php if (isset($_GET['error'])) {?>
			<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['error']); ?>
			</div>
			<?php } ?>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<div class="bg-white rounded-lg shadow-digitazio p-8">
				<form method="POST" action="app/add-request.php" class="space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Request Title *</label>
							<input type="text" name="title" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="Brief title for your request" required>
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Category *</label>
							<select name="category" 
									class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
								<option value="">Select Category</option>
								<option value="equipment">Equipment Request</option>
								<option value="leave">Leave Request</option>
								<option value="support">Technical Support</option>
								<option value="other">Other</option>
							</select>
						</div>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Priority Level *</label>
						<select name="priority" 
								class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
							<option value="low">Low Priority</option>
							<option value="medium" selected>Medium Priority</option>
							<option value="high">High Priority</option>
							<option value="urgent">Urgent</option>
						</select>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Request Message *</label>
						<textarea name="message" rows="6" 
								  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300 resize-vertical" 
								  placeholder="Provide detailed information about your request..." required></textarea>
						<p class="text-gray-500 text-body-sm mt-2">Please provide as much detail as possible to help the admin understand your request.</p>
					</div>

					<div class="flex gap-4 pt-4">
						<button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
							<i class="fa fa-paper-plane mr-2"></i>Submit Request
						</button>
						<a href="my_requests.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
							<i class="fa fa-times mr-2"></i>Cancel
						</a>
					</div>
				</form>
			</div>
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
