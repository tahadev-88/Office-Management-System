<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Request.php";
    
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    
    if ($filter == 'all') {
        $requests = get_all_requests($conn);
    } else {
        $requests = get_requests_by_status($conn, $filter);
    }
    
    $counts = get_request_counts($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Employee Requests | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-2">Employee Requests</h1>
				<p class="text-subheading-sm text-gray-600 font-poppins">Manage and respond to employee requests</p>
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

			<!-- Summary Cards -->
			<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-blue-100 text-blue-600">
							<i class="fa fa-inbox text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Total Requests</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['total']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
							<i class="fa fa-clock-o text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Pending</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['pending']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-blue-100 text-blue-600">
							<i class="fa fa-eye text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">In Review</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['in_review']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-green-100 text-green-600">
							<i class="fa fa-check text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Approved</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['approved']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-red-100 text-red-600">
							<i class="fa fa-times text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Rejected</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['rejected']?></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Filter Buttons -->
			<div class="mb-6 flex flex-wrap gap-2">
				<a href="requests.php?filter=all" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'all' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					All Requests
				</a>
				<a href="requests.php?filter=pending" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'pending' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Pending
				</a>
				<a href="requests.php?filter=in_review" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'in_review' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					In Review
				</a>
				<a href="requests.php?filter=approved" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'approved' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Approved
				</a>
				<a href="requests.php?filter=rejected" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'rejected' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Rejected
				</a>
			</div>

			<div class="bg-white rounded-lg shadow-digitazio overflow-hidden">
				<?php if ($requests != 0) { ?>
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Employee</th>
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
									<div class="font-poppins text-body font-medium text-eerie-black"><?=$request['employee_name']?></div>
								</td>
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
									<div class="flex gap-2">
										<button onclick="viewRequest(<?=$request['id']?>)" class="text-atlantis hover:text-atlantis-dark font-poppins text-body font-medium">
											<i class="fa fa-eye mr-1"></i>View
										</button>
										<?php if ($request['status'] == 'pending' || $request['status'] == 'in_review') { ?>
										<button onclick="reviewRequest(<?=$request['id']?>)" class="text-blue-600 hover:text-blue-800 font-poppins text-body font-medium">
											<i class="fa fa-edit mr-1"></i>Review
										</button>
										<?php } ?>
									</div>
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
					<p class="text-body text-gray-400 font-poppins">No employee requests match the current filter.</p>
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

	<!-- Review Request Modal -->
	<div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
		<div class="flex items-center justify-center min-h-screen p-4">
			<div class="bg-white rounded-lg shadow-digitazio max-w-2xl w-full">
				<div class="p-6">
					<div class="flex justify-between items-center mb-4">
						<h3 class="text-subheading font-poppins font-semibold text-eerie-black">Review Request</h3>
						<button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
							<i class="fa fa-times text-xl"></i>
						</button>
					</div>
					<div id="reviewContent">
						<!-- Content will be loaded here -->
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(8)");
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

	function reviewRequest(id) {
		fetch('app/get-request-review.php?id=' + id)
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					document.getElementById('reviewContent').innerHTML = data.html;
					document.getElementById('reviewModal').classList.remove('hidden');
				} else {
					alert('Error loading request for review');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Error loading request for review');
			});
	}

	function closeModal() {
		document.getElementById('requestModal').classList.add('hidden');
	}

	function closeReviewModal() {
		document.getElementById('reviewModal').classList.add('hidden');
	}

	// Close modals when clicking outside
	document.getElementById('requestModal').addEventListener('click', function(e) {
		if (e.target === this) {
			closeModal();
		}
	});

	document.getElementById('reviewModal').addEventListener('click', function(e) {
		if (e.target === this) {
			closeReviewModal();
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
