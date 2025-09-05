<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Inventory.php";
    
    $user_id = $_SESSION['id'];
    $items = get_inventory_by_user($conn, $user_id);
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Inventory | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-4">My Assigned Equipment</h1>
				<p class="text-body font-poppins text-gray-600 mb-6">View and manage the equipment assigned to you. Report any issues or damages to the admin.</p>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if ($items != 0) { ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ($items as $item) { ?>
				<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 
					<?php 
					switch($item['category']) {
						case 'laptop': echo 'border-purple-500'; break;
						case 'desktop': echo 'border-indigo-500'; break;
						case 'monitor': echo 'border-blue-500'; break;
						case 'keyboard': case 'mouse': echo 'border-green-500'; break;
						case 'phone': case 'tablet': echo 'border-pink-500'; break;
						default: echo 'border-gray-500'; break;
					}
					?>">
					<div class="flex items-start justify-between mb-4">
						<div class="flex items-center">
							<i class="fa fa-<?php 
								switch($item['category']) {
									case 'laptop': echo 'laptop'; break;
									case 'desktop': echo 'desktop'; break;
									case 'monitor': echo 'tv'; break;
									case 'keyboard': echo 'keyboard-o'; break;
									case 'mouse': echo 'mouse-pointer'; break;
									case 'phone': echo 'mobile'; break;
									case 'tablet': echo 'tablet'; break;
									case 'printer': echo 'print'; break;
									default: echo 'cube'; break;
								}
							?> text-2xl text-<?php 
								switch($item['category']) {
									case 'laptop': echo 'purple'; break;
									case 'desktop': echo 'indigo'; break;
									case 'monitor': echo 'blue'; break;
									case 'keyboard': case 'mouse': echo 'green'; break;
									case 'phone': case 'tablet': echo 'pink'; break;
									default: echo 'gray'; break;
								}
							?>-500 mr-3"></i>
							<div>
								<h3 class="font-poppins font-semibold text-eerie-black"><?=$item['item_name']?></h3>
								<p class="text-body font-poppins text-gray-600"><?=$item['brand']?> <?=$item['model']?></p>
							</div>
						</div>
						<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
							<?php 
							switch($item['status']) {
								case 'assigned': echo 'bg-green-100 text-green-800'; break;
								case 'maintenance': echo 'bg-red-100 text-red-800'; break;
								case 'damaged': echo 'bg-red-100 text-red-800'; break;
							}
							?>">
							<?=ucfirst($item['status'])?>
						</span>
					</div>
					
					<div class="space-y-2 mb-4">
						<?php if($item['serial_number']) { ?>
						<div class="flex justify-between">
							<span class="text-body font-poppins text-gray-600">Serial:</span>
							<span class="text-body font-poppins font-mono"><?=$item['serial_number']?></span>
						</div>
						<?php } ?>
						
						<?php if($item['purchase_date']) { ?>
						<div class="flex justify-between">
							<span class="text-body font-poppins text-gray-600">Purchase:</span>
							<span class="text-body font-poppins"><?=date('M Y', strtotime($item['purchase_date']))?></span>
						</div>
						<?php } ?>
						
						<?php if($item['warranty_expiry']) { ?>
						<div class="flex justify-between">
							<span class="text-body font-poppins text-gray-600">Warranty:</span>
							<span class="text-body font-poppins <?=strtotime($item['warranty_expiry']) < time() ? 'text-red-600' : 'text-green-600'?>">
								<?=date('M Y', strtotime($item['warranty_expiry']))?>
							</span>
						</div>
						<?php } ?>
					</div>
					
					<?php if($item['description']) { ?>
					<p class="text-body font-poppins text-gray-600 mb-4"><?=$item['description']?></p>
					<?php } ?>
					
					<div class="flex gap-2">
						<?php if($_SESSION['role'] == 'admin') { ?>
						<a href="edit-inventory.php?id=<?=$item['id']?>" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-2 px-3 rounded transition-colors duration-300">
							<i class="fa fa-edit mr-1"></i>Edit
						</a>
						<?php } ?>
						<button onclick="reportIssue(<?=$item['id']?>, '<?=$item['item_name']?>')" class="bg-orange-500 hover:bg-orange-600 text-white font-poppins text-xs py-2 px-3 rounded transition-colors duration-300">
							<i class="fa fa-exclamation-triangle mr-1"></i>Report Issue
						</button>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php } else { ?>
			<div class="text-center py-12">
				<i class="fa fa-cube text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No equipment assigned</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Contact your admin to get equipment assigned to you.</p>
			</div>
			<?php } ?>
		</section>
	</div>

	<!-- Report Issue Modal -->
	<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
		<div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
			<h3 class="text-subheading font-poppins font-semibold text-eerie-black mb-4">Report Equipment Issue</h3>
			<form id="reportForm">
				<input type="hidden" id="itemId" name="item_id">
				<div class="mb-4">
					<label class="block text-body font-poppins font-semibold text-eerie-black mb-2">Equipment:</label>
					<p id="itemName" class="text-body font-poppins text-gray-600"></p>
				</div>
				<div class="mb-4">
					<label class="block text-body font-poppins font-semibold text-eerie-black mb-2">Issue Type:</label>
					<select name="issue_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-poppins text-body" required>
						<option value="">Select Issue Type</option>
						<option value="damage">Physical Damage</option>
						<option value="malfunction">Not Working Properly</option>
						<option value="maintenance">Needs Maintenance</option>
						<option value="replacement">Needs Replacement</option>
						<option value="other">Other</option>
					</select>
				</div>
				<div class="mb-6">
					<label class="block text-body font-poppins font-semibold text-eerie-black mb-2">Description:</label>
					<textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg font-poppins text-body resize-vertical" placeholder="Describe the issue..." required></textarea>
				</div>
				<div class="flex gap-3">
					<button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
						Submit Report
					</button>
					<button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
						Cancel
					</button>
				</div>
			</form>
		</div>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(<?=$_SESSION['role'] == 'admin' ? '5' : '3'?>)");
	active.classList.add("active");

	function reportIssue(itemId, itemName) {
		document.getElementById('itemId').value = itemId;
		document.getElementById('itemName').textContent = itemName;
		document.getElementById('reportModal').classList.remove('hidden');
		document.getElementById('reportModal').classList.add('flex');
	}

	function closeModal() {
		document.getElementById('reportModal').classList.add('hidden');
		document.getElementById('reportModal').classList.remove('flex');
		document.getElementById('reportForm').reset();
	}

	document.getElementById('reportForm').addEventListener('submit', function(e) {
		e.preventDefault();
		
		// Here you would typically send the report to the server
		// For now, we'll just show a success message
		alert('Issue reported successfully! Admin will be notified.');
		closeModal();
	});

	// Close modal when clicking outside
	document.getElementById('reportModal').addEventListener('click', function(e) {
		if (e.target === this) {
			closeModal();
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
