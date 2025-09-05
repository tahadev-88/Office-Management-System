<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Inventory.php";
    include "app/Model/User.php";
    
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    
    if($filter == 'available') {
        $items = get_inventory_by_status($conn, 'available');
        $page_title = "Available Items";
    } elseif($filter == 'assigned') {
        $items = get_inventory_by_status($conn, 'assigned');
        $page_title = "Assigned Items";
    } elseif($filter == 'maintenance') {
        $items = get_inventory_by_status($conn, 'maintenance');
        $page_title = "Items in Maintenance";
    } elseif($category) {
        $items = get_inventory_by_category($conn, $category);
        $page_title = ucfirst($category) . " Items";
    } else {
        $items = get_all_inventory_items($conn);
        $page_title = "All Inventory Items";
    }
    
    $summary = get_inventory_summary($conn);
    $users = get_all_users($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventory Management | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-6">Inventory Management</h1>
				
				<!-- Inventory Summary Cards -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
					<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-blue-600 font-poppins text-body font-medium">Total Items</p>
								<p class="text-2xl font-poppins font-bold text-blue-800"><?=$summary['total_items']?></p>
							</div>
							<i class="fa fa-cubes text-3xl text-blue-500"></i>
						</div>
					</div>
					
					<div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-green-600 font-poppins text-body font-medium">Available</p>
								<p class="text-2xl font-poppins font-bold text-green-800"><?=$summary['available']?></p>
							</div>
							<i class="fa fa-check-circle text-3xl text-green-500"></i>
						</div>
					</div>
					
					<div class="bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-orange-600 font-poppins text-body font-medium">Assigned</p>
								<p class="text-2xl font-poppins font-bold text-orange-800"><?=$summary['assigned']?></p>
							</div>
							<i class="fa fa-user text-3xl text-orange-500"></i>
						</div>
					</div>
					
					<div class="bg-red-50 border border-red-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-red-600 font-poppins text-body font-medium">Maintenance</p>
								<p class="text-2xl font-poppins font-bold text-red-800"><?=$summary['maintenance']?></p>
							</div>
							<i class="fa fa-wrench text-3xl text-red-500"></i>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="flex flex-wrap gap-3 mb-6">
					<a href="add-inventory.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-plus mr-2"></i>Add Item
					</a>
					<a href="inventory.php?filter=available" class="bg-green-500 hover:bg-green-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Available
					</a>
					<a href="inventory.php?filter=assigned" class="bg-orange-500 hover:bg-orange-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Assigned
					</a>
					<a href="inventory.php?filter=maintenance" class="bg-red-500 hover:bg-red-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Maintenance
					</a>
					<a href="inventory.php?category=laptop" class="bg-purple-500 hover:bg-purple-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Laptops
					</a>
					<a href="inventory.php?category=desktop" class="bg-indigo-500 hover:bg-indigo-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Desktops
					</a>
					<a href="inventory.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						All Items
					</a>
				</div>
				
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black"><?=$page_title?> (<?=is_array($items) ? count($items) : 0?>)</h2>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if ($items != 0) { ?>
			<div class="overflow-x-auto bg-white rounded-lg shadow-digitazio">
				<table class="w-full table-auto">
					<thead class="bg-atlantis text-white">
						<tr>
							<th class="px-4 py-3 text-left font-poppins font-semibold">#</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Item</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Category</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Brand/Model</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Serial Number</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Assigned To</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Status</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($items as $item) { ?>
						<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
							<td class="px-4 py-3 font-poppins text-body"><?=++$i?></td>
							<td class="px-4 py-3 font-poppins text-body font-medium"><?=$item['item_name']?></td>
							<td class="px-4 py-3">
								<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
									<?php 
									switch($item['category']) {
										case 'laptop': echo 'bg-purple-100 text-purple-800'; break;
										case 'desktop': echo 'bg-indigo-100 text-indigo-800'; break;
										case 'monitor': echo 'bg-blue-100 text-blue-800'; break;
										case 'keyboard': case 'mouse': echo 'bg-green-100 text-green-800'; break;
										case 'phone': case 'tablet': echo 'bg-pink-100 text-pink-800'; break;
										default: echo 'bg-gray-100 text-gray-800'; break;
									}
									?>">
									<?=ucfirst($item['category'])?>
								</span>
							</td>
							<td class="px-4 py-3 font-poppins text-body"><?=$item['brand']?> <?=$item['model']?></td>
							<td class="px-4 py-3 font-poppins text-body font-mono text-xs"><?=$item['serial_number']?></td>
							<td class="px-4 py-3 font-poppins text-body">
								<?=$item['assigned_user'] ? $item['assigned_user'] : '<span class="text-gray-400">Unassigned</span>'?>
							</td>
							<td class="px-4 py-3">
								<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
									<?php 
									switch($item['status']) {
										case 'available': echo 'bg-green-100 text-green-800'; break;
										case 'assigned': echo 'bg-orange-100 text-orange-800'; break;
										case 'maintenance': echo 'bg-red-100 text-red-800'; break;
										case 'damaged': echo 'bg-red-100 text-red-800'; break;
										case 'retired': echo 'bg-gray-100 text-gray-800'; break;
									}
									?>">
									<?=ucfirst($item['status'])?>
								</span>
							</td>
							<td class="px-4 py-3">
								<div class="flex space-x-2">
									<a href="edit-inventory.php?id=<?=$item['id']?>" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300">
										Edit
									</a>
									<a href="delete-inventory.php?id=<?=$item['id']?>" class="bg-red-500 hover:bg-red-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300" onclick="return confirm('Are you sure you want to delete this item?')">
										Delete
									</a>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } else { ?>
			<div class="text-center py-12">
				<i class="fa fa-cube text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No inventory items found</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Start by adding your first inventory item!</p>
				<a href="add-inventory.php" class="inline-block mt-4 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>Add Item
				</a>
			</div>
			<?php } ?>
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
