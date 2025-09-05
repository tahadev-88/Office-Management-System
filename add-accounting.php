<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Accounting Entry | Digitazio Panel</title>
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
					Add Accounting Entry
					<a href="accounting.php" class="text-atlantis hover:text-atlantis-dark transition-colors duration-300 ml-2">
						<i class="fa fa-arrow-left mr-2"></i>Back to Accounting
					</a>
				</h1>
			</div>

			<?php if (isset($_GET['error'])) {?>
			<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['error']); ?>
			</div>
			<?php } ?>

			<div class="bg-white rounded-lg shadow-digitazio p-8">
				<form method="POST" action="app/add-accounting.php" class="space-y-6">
					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Category</label>
						<select name="category" id="category" 
								class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
							<option value="">Select Category</option>
							<option value="assets">Assets</option>
							<option value="shares">Shares</option>
							<option value="revenue">Revenue</option>
							<option value="expenses">Expenses</option>
							<option value="salaries">Salaries</option>
						</select>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Type</label>
						<input type="text" name="type" 
							   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
							   placeholder="e.g., Equipment, Client Payment, Office Supplies" required>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Description</label>
						<textarea name="description" rows="4" 
								  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300 resize-vertical" 
								  placeholder="Detailed description of the transaction" required></textarea>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Amount ($)</label>
						<input type="number" name="amount" step="0.01" min="0" 
							   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
							   placeholder="0.00" required>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Date</label>
						<input type="date" name="date" 
							   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
							   value="<?=date('Y-m-d')?>" required>
					</div>

					<div class="flex gap-4 pt-4">
						<button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
							<i class="fa fa-save mr-2"></i>Add Entry
						</button>
						<a href="accounting.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
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

	// Update form styling based on category selection
	document.getElementById('category').addEventListener('change', function() {
		const category = this.value;
		const form = this.closest('form');
		
		// Remove existing category classes
		form.classList.remove('border-green-200', 'border-blue-200', 'border-red-200', 'border-purple-200', 'border-orange-200');
		
		// Add category-specific styling
		switch(category) {
			case 'assets':
				form.classList.add('border-green-200');
				break;
			case 'revenue':
				form.classList.add('border-blue-200');
				break;
			case 'expenses':
				form.classList.add('border-red-200');
				break;
			case 'salaries':
				form.classList.add('border-purple-200');
				break;
			case 'shares':
				form.classList.add('border-orange-200');
				break;
		}
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
