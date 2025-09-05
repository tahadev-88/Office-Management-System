<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Inventory.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: inventory.php");
    	 exit();
    }
    $id = $_GET['id'];
    $item = get_inventory_item_by_id($conn, $id);

    if ($item == 0) {
    	 header("Location: inventory.php");
    	 exit();
    }
    
    $users = get_all_users($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Inventory Item | Digitazio Panel</title>
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
					Edit Inventory Item
					<a href="inventory.php" class="text-atlantis hover:text-atlantis-dark transition-colors duration-300 ml-2">
						<i class="fa fa-arrow-left mr-2"></i>Back to Inventory
					</a>
				</h1>
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
				<form method="POST" action="app/update-inventory.php" class="space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Item Name *</label>
							<input type="text" name="item_name" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="e.g., Dell Laptop, HP Monitor" 
								   value="<?=$item['item_name']?>" required>
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Category *</label>
							<select name="category" 
									class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
								<option value="">Select Category</option>
								<option value="laptop" <?=$item['category'] == 'laptop' ? 'selected' : ''?>>Laptop</option>
								<option value="desktop" <?=$item['category'] == 'desktop' ? 'selected' : ''?>>Desktop</option>
								<option value="monitor" <?=$item['category'] == 'monitor' ? 'selected' : ''?>>Monitor</option>
								<option value="keyboard" <?=$item['category'] == 'keyboard' ? 'selected' : ''?>>Keyboard</option>
								<option value="mouse" <?=$item['category'] == 'mouse' ? 'selected' : ''?>>Mouse</option>
								<option value="cable" <?=$item['category'] == 'cable' ? 'selected' : ''?>>Cable</option>
								<option value="usb" <?=$item['category'] == 'usb' ? 'selected' : ''?>>USB Drive</option>
								<option value="printer" <?=$item['category'] == 'printer' ? 'selected' : ''?>>Printer</option>
								<option value="phone" <?=$item['category'] == 'phone' ? 'selected' : ''?>>Phone</option>
								<option value="tablet" <?=$item['category'] == 'tablet' ? 'selected' : ''?>>Tablet</option>
								<option value="other" <?=$item['category'] == 'other' ? 'selected' : ''?>>Other</option>
							</select>
						</div>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Brand</label>
							<input type="text" name="brand" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="e.g., Dell, HP, Apple"
								   value="<?=$item['brand']?>">
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Model</label>
							<input type="text" name="model" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="e.g., Inspiron 15, EliteBook"
								   value="<?=$item['model']?>">
						</div>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Serial Number</label>
							<input type="text" name="serial_number" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="Unique serial number"
								   value="<?=$item['serial_number']?>">
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Status</label>
							<select name="status" 
									class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
								<option value="available" <?=$item['status'] == 'available' ? 'selected' : ''?>>Available</option>
								<option value="assigned" <?=$item['status'] == 'assigned' ? 'selected' : ''?>>Assigned</option>
								<option value="maintenance" <?=$item['status'] == 'maintenance' ? 'selected' : ''?>>Maintenance</option>
								<option value="damaged" <?=$item['status'] == 'damaged' ? 'selected' : ''?>>Damaged</option>
								<option value="retired" <?=$item['status'] == 'retired' ? 'selected' : ''?>>Retired</option>
							</select>
						</div>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Assign To Employee</label>
						<select name="assigned_to" 
								class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300">
							<option value="">Unassigned</option>
							<?php if ($users != 0) { 
								foreach ($users as $user) { ?>
								<option value="<?=$user['id']?>" <?=$item['assigned_to'] == $user['id'] ? 'selected' : ''?>><?=$user['full_name']?></option>
							<?php } } ?>
						</select>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Purchase Date</label>
							<input type="date" name="purchase_date" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300"
								   value="<?=$item['purchase_date']?>">
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Purchase Price ($)</label>
							<input type="number" name="purchase_price" step="0.01" min="0" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" 
								   placeholder="0.00"
								   value="<?=$item['purchase_price']?>">
						</div>

						<div>
							<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Warranty Expiry</label>
							<input type="date" name="warranty_expiry" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300"
								   value="<?=$item['warranty_expiry']?>">
						</div>
					</div>

					<div>
						<label class="block text-subheading-sm font-poppins font-semibold text-eerie-black mb-2">Description</label>
						<textarea name="description" rows="4" 
								  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300 resize-vertical" 
								  placeholder="Additional details about the item..."><?=$item['description']?></textarea>
					</div>

					<input type="hidden" name="id" value="<?=$item['id']?>">

					<div class="flex gap-4 pt-4">
						<button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
							<i class="fa fa-save mr-2"></i>Update Item
						</button>
						<a href="inventory.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
							<i class="fa fa-times mr-2"></i>Cancel
						</a>
					</div>
				</form>
			</div>
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(5)");
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
