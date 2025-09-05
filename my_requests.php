<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Request.php";
    
    $requests = get_requests_by_employee($conn, $_SESSION['id']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Requests | Digitazio Panel</title>
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
			<div class="mb-8 flex justify-between items-center">
				<div>
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-2">My Requests</h1>
					<p class="text-subheading-sm text-gray-600 font-poppins">View and track your submitted requests</p>
				</div>
				<a href="create_request.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>New Request
				</a>
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

			<div class="bg-white rounded-lg shadow-digitazio overflow-hidden">
				<?php if ($requests != 0) { ?>
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Title</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Category</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Priority</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Status</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Submitted</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Actions</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200">
							<?php foreach ($requests as $request) { 
								// Status badge colors
								$statusColors = [
									'pending' => 'bg-yellow-100 text-yellow-800',
									'approved' => 'bg-green-100 text-green-800',
									'rejected' => 'bg-red-100 text-red-800',
									'in_review' => 'bg-blue-100 text-blue-800'
								];
								
								// Priority badge colors
								$priorityColors = [
									'low' => 'bg-gray-100 text-gray-800',
									'medium' => 'bg-blue-100 text-blue-800',
									'high' => 'bg-orange-100 text-orange-800',
									'urgent' => 'bg-red-100 text-red-800'
								];
							?>
							<tr class="hover:bg-gray-50 transition-colors duration-200">
								<td class="px-6 py-4">
									<div class="font-poppins text-body font-medium text-eerie-black"><?=$request['title']?></div>
									<div class="font-poppins text-body-sm text-gray-500 mt-1"><?=substr($request['message'], 0, 60)?>...</div>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium bg-gray-100 text-gray-800 capitalize">
										<?=$request['category']?>
									</span>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium <?=$priorityColors[$request['priority']]?> capitalize">
										<?=$request['priority']?>
									</span>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium <?=$statusColors[$request['status']]?> capitalize">
										<?=str_replace('_', ' ', $request['status'])?>
									</span>
								</td>
								<td class="px-6 py-4 font-poppins text-body text-gray-500">
									<?=date('M d, Y', strtotime($request['created_at']))?>
								</td>
								<td class="px-6 py-4">
									<button onclick="viewRequest(<?=$request['id']?>)" class="text-atlantis hover:text-atlantis-dark font-poppins text-body font-medium">
										<i class="fa fa-eye mr-1"></i>View
									</button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php } else { ?>
				<div class="text-center py-12">
					<i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
					<h3 class="text-subheading font-poppins font-semibold text-gray-500 mb-2">No Requests Found</h3>
					<p class="text-body text-gray-400 font-poppins mb-6">You haven't submitted any requests yet.</p>
					<a href="create_request.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
						<i class="fa fa-plus mr-2"></i>Create Your First Request
					</a>
				</div>
				<?php } ?>
			</div>
		</section>
	</div>

	<!-- Request Details Modal -->
	<div id="requestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
		<div class="flex items-center justify-center min-h-screen p-4">
			<div class="bg-white rounded-lg shadow-digitazio max-w-2xl w-full max-h-screen overflow-y-auto">
				<div class="p-6">
					<div class="flex justify-between items-center mb-4">
						<h3 class="text-subheading font-poppins font-semibold text-eerie-black">Request Details</h3>
						<button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
							<i class="fa fa-times text-xl"></i>
						</button>
					</div>
					<div id="modalContent">
						<!-- Content will be loaded here -->
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(4)");
	active.classList.add("active");

	function viewRequest(id) {
		fetch('app/get-request-details.php?id=' + id)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					document.getElementById('modalContent').innerHTML = data.html;
					document.getElementById('requestModal').classList.remove('hidden');
				} else {
					alert('Error loading request details');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Error loading request details');
			});
	}

	function closeModal() {
		document.getElementById('requestModal').classList.add('hidden');
	}

	// Close modal when clicking outside
	document.getElementById('requestModal').addEventListener('click', function(e) {
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
