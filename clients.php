<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Client.php";
    
    $clients = get_all_clients($conn);
    
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Clients | Digitazio Panel</title>
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
	<script src="js/sidebar.js"></script>
</head>
<body class="font-poppins bg-gray-50">
	<input type="checkbox" id="checkbox" class="hidden">
	<?php include "inc/header.php" ?>
	<div class="flex">
		<?php include "inc/nav.php" ?>
		<section class="flex-1 p-8 bg-white min-h-screen">
			<div class="mb-8">
				<div class="flex justify-between items-center mb-6">
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Client Management</h1>
					<button onclick="openAddModal()" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-plus mr-2"></i>Add New Client
					</button>
				</div>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<i class="fa fa-check-circle mr-2"></i><?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if (isset($_GET['error'])) {?>
			<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<i class="fa fa-exclamation-triangle mr-2"></i><?php echo stripcslashes($_GET['error']); ?>
			</div>
			<?php } ?>

			<!-- Search and Filter Section -->
			<div class="mb-6 bg-white rounded-lg shadow-digitazio p-6">
				<div class="flex flex-col md:flex-row gap-4">
					<div class="flex-1">
						<input type="text" id="searchInput" placeholder="Search clients..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300">
					</div>
					<div class="flex gap-2">
						<select id="platformFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body">
							<option value="">All Platforms</option>
							<option value="Website">Website</option>
							<option value="Social Media">Social Media</option>
							<option value="Referral">Referral</option>
							<option value="Cold Call">Cold Call</option>
							<option value="Email Marketing">Email Marketing</option>
							<option value="Other">Other</option>
						</select>
						<button onclick="clearFilters()" class="px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-300 font-poppins text-body">
							<i class="fa fa-refresh mr-1"></i>Clear
						</button>
					</div>
				</div>
			</div>

			<?php if ($clients != 0) { ?>
			<div class="overflow-x-auto bg-white rounded-lg shadow-digitazio">
				<table class="w-full table-auto" id="clientsTable">
					<thead class="bg-atlantis text-white">
						<tr>
							<th class="px-6 py-4 text-left font-poppins font-semibold">#</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Client Name</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Contact Info</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Salesman</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Platform</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Added Date</th>
							<th class="px-6 py-4 text-left font-poppins font-semibold">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($clients as $client) { ?>
						<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
							<td class="px-6 py-4 font-poppins text-body"><?=++$i?></td>
							<td class="px-6 py-4 font-poppins text-body font-medium"><?=$client['client_name']?></td>
							<td class="px-6 py-4 font-poppins text-body"><?=$client['contact_info']?></td>
							<td class="px-6 py-4 font-poppins text-body"><?=$client['salesman']?></td>
							<td class="px-6 py-4">
								<span class="px-3 py-1 rounded-full text-xs font-poppins font-medium
									<?php 
									switch($client['platform']) {
										case 'Website': echo 'bg-blue-100 text-blue-800'; break;
										case 'Social Media': echo 'bg-purple-100 text-purple-800'; break;
										case 'Referral': echo 'bg-green-100 text-green-800'; break;
										case 'Cold Call': echo 'bg-orange-100 text-orange-800'; break;
										case 'Email Marketing': echo 'bg-yellow-100 text-yellow-800'; break;
										default: echo 'bg-gray-100 text-gray-800';
									}
									?>">
									<?=$client['platform']?>
								</span>
							</td>
							<td class="px-6 py-4 font-poppins text-body">
								<?=date('M j, Y', strtotime($client['created_at']))?>
							</td>
							<td class="px-6 py-4">
								<div class="flex space-x-2">
									<button data-client-id="<?=$client['id']?>" data-client-name="<?=htmlspecialchars($client['client_name'])?>" data-contact-info="<?=htmlspecialchars($client['contact_info'])?>" data-salesman="<?=htmlspecialchars($client['salesman'])?>" data-platform="<?=htmlspecialchars($client['platform'])?>" class="edit-btn bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-2 px-4 rounded transition-colors duration-300">
										<i class="fa fa-edit mr-1"></i>Edit
									</button>
									<button data-client-id="<?=$client['id']?>" data-client-name="<?=htmlspecialchars($client['client_name'])?>" class="delete-btn bg-red-500 hover:bg-red-600 text-white font-poppins text-xs py-2 px-4 rounded transition-colors duration-300">
										<i class="fa fa-trash mr-1"></i>Delete
									</button>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }else { ?>
			<div class="text-center py-12">
				<i class="fa fa-handshake-o text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No clients found</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Add your first client to get started!</p>
				<button onclick="openAddModal()" class="inline-block mt-4 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>Add Client
				</button>
			</div>
			<?php }?>

		</section>
	</div>

	<!-- Add Client Modal -->
	<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
		<div class="bg-white rounded-lg shadow-digitazio p-8 max-w-md w-full mx-4">
			<div class="flex justify-between items-center mb-6">
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black">Add New Client</h2>
				<button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
					<i class="fa fa-times text-xl"></i>
				</button>
			</div>
			<form method="POST" action="app/add-client.php" class="space-y-4">
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Client Name</label>
					<input type="text" name="client_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter client name" required>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Contact Info</label>
					<textarea name="contact_info" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter contact information (phone, email, address)" required></textarea>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Salesman</label>
					<input type="text" name="salesman" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter salesman name" required>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Platform</label>
					<select name="platform" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" required>
						<option value="">Select Platform</option>
						<option value="Website">Website</option>
						<option value="Social Media">Social Media</option>
						<option value="Referral">Referral</option>
						<option value="Cold Call">Cold Call</option>
						<option value="Email Marketing">Email Marketing</option>
						<option value="Other">Other</option>
					</select>
				</div>
				<div class="flex space-x-4 pt-4">
					<button type="submit" class="flex-1 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-save mr-2"></i>Add Client
					</button>
					<button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
						<i class="fa fa-times mr-2"></i>Cancel
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Edit Client Modal -->
	<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
		<div class="bg-white rounded-lg shadow-digitazio p-8 max-w-md w-full mx-4">
			<div class="flex justify-between items-center mb-6">
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black">Edit Client</h2>
				<button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
					<i class="fa fa-times text-xl"></i>
				</button>
			</div>
			<form method="POST" action="app/update-client.php" class="space-y-4">
				<input type="hidden" name="id" id="editClientId">
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Client Name</label>
					<input type="text" name="client_name" id="editClientName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter client name" required>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Contact Info</label>
					<textarea name="contact_info" id="editContactInfo" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter contact information" required></textarea>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Salesman</label>
					<input type="text" name="salesman" id="editSalesman" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" placeholder="Enter salesman name" required>
				</div>
				<div>
					<label class="block text-subheading-sm font-poppins font-medium text-eerie-black mb-2">Platform</label>
					<select name="platform" id="editPlatform" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-colors duration-300" required>
						<option value="">Select Platform</option>
						<option value="Website">Website</option>
						<option value="Social Media">Social Media</option>
						<option value="Referral">Referral</option>
						<option value="Cold Call">Cold Call</option>
						<option value="Email Marketing">Email Marketing</option>
						<option value="Other">Other</option>
					</select>
				</div>
				<div class="flex space-x-4 pt-4">
					<button type="submit" class="flex-1 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-save mr-2"></i>Update Client
					</button>
					<button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
						<i class="fa fa-times mr-2"></i>Cancel
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Delete Confirmation Modal -->
	<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
		<div class="bg-white rounded-lg shadow-digitazio p-8 max-w-md w-full mx-4">
			<div class="text-center">
				<i class="fa fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black mb-4">Delete Client</h2>
				<p class="text-body font-poppins text-gray-600 mb-6">Are you sure you want to delete <span id="deleteClientName" class="font-semibold"></span>? This action cannot be undone.</p>
				<div class="flex space-x-4">
					<button onclick="deleteClient()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
						<i class="fa fa-trash mr-2"></i>Delete
					</button>
					<button onclick="closeDeleteModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
						<i class="fa fa-times mr-2"></i>Cancel
					</button>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(5)");
	active.classList.add("active");

	let deleteClientId = null;

	// Modal Functions
	function openAddModal() {
		document.getElementById('addModal').classList.remove('hidden');
		document.getElementById('addModal').classList.add('flex');
	}

	function closeAddModal() {
		document.getElementById('addModal').classList.add('hidden');
		document.getElementById('addModal').classList.remove('flex');
	}

	function openEditModal(id, name, contact, salesman, platform) {
		document.getElementById('editClientId').value = id;
		document.getElementById('editClientName').value = name;
		document.getElementById('editContactInfo').value = contact;
		document.getElementById('editSalesman').value = salesman;
		document.getElementById('editPlatform').value = platform;
		document.getElementById('editModal').classList.remove('hidden');
		document.getElementById('editModal').classList.add('flex');
	}

	function closeEditModal() {
		document.getElementById('editModal').classList.add('hidden');
		document.getElementById('editModal').classList.remove('flex');
	}

	function confirmDelete(id, name) {
		deleteClientId = id;
		document.getElementById('deleteClientName').textContent = name;
		document.getElementById('deleteModal').classList.remove('hidden');
		document.getElementById('deleteModal').classList.add('flex');
	}

	function closeDeleteModal() {
		document.getElementById('deleteModal').classList.add('hidden');
		document.getElementById('deleteModal').classList.remove('flex');
		deleteClientId = null;
	}

	function deleteClient() {
		if (deleteClientId) {
			window.location.href = 'app/delete-client.php?id=' + deleteClientId;
		}
	}

	// Search and Filter Functions
	function filterTable() {
		const searchTerm = document.getElementById('searchInput').value.toLowerCase();
		const platformFilter = document.getElementById('platformFilter').value;
		const table = document.getElementById('clientsTable');
		const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

		for (let i = 0; i < rows.length; i++) {
			const cells = rows[i].getElementsByTagName('td');
			const clientName = cells[1].textContent.toLowerCase();
			const contactInfo = cells[2].textContent.toLowerCase();
			const salesman = cells[3].textContent.toLowerCase();
			const platform = cells[4].textContent.trim();

			const matchesSearch = clientName.includes(searchTerm) || 
								contactInfo.includes(searchTerm) || 
								salesman.includes(searchTerm);
			const matchesPlatform = platformFilter === '' || platform === platformFilter;

			if (matchesSearch && matchesPlatform) {
				rows[i].style.display = '';
			} else {
				rows[i].style.display = 'none';
			}
		}
	}

	function clearFilters() {
		document.getElementById('searchInput').value = '';
		document.getElementById('platformFilter').value = '';
		filterTable();
	}

	// Event Listeners
	document.getElementById('searchInput').addEventListener('keyup', filterTable);
	document.getElementById('platformFilter').addEventListener('change', filterTable);

	// Edit button event listeners
	document.querySelectorAll('.edit-btn').forEach(function(button) {
		button.addEventListener('click', function() {
			const id = this.getAttribute('data-client-id');
			const name = this.getAttribute('data-client-name');
			const contact = this.getAttribute('data-contact-info');
			const salesman = this.getAttribute('data-salesman');
			const platform = this.getAttribute('data-platform');
			openEditModal(id, name, contact, salesman, platform);
		});
	});

	// Delete button event listeners
	document.querySelectorAll('.delete-btn').forEach(function(button) {
		button.addEventListener('click', function() {
			const id = this.getAttribute('data-client-id');
			const name = this.getAttribute('data-client-name');
			confirmDelete(id, name);
		});
	});

	// Close modals when clicking outside
	window.onclick = function(event) {
		const addModal = document.getElementById('addModal');
		const editModal = document.getElementById('editModal');
		const deleteModal = document.getElementById('deleteModal');
		
		if (event.target === addModal) {
			closeAddModal();
		}
		if (event.target === editModal) {
			closeEditModal();
		}
		if (event.target === deleteModal) {
			closeDeleteModal();
		}
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
