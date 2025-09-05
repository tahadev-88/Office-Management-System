<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Report.php";
    
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    
    if ($filter == 'all') {
        $reports = get_all_reports($conn);
    } else {
        $reports = get_reports_by_type($conn, $filter);
    }
    
    $counts = get_report_counts($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Reports Management | Digitazio Panel</title>
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
					<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-2">Reports Management</h1>
					<p class="text-subheading-sm text-gray-600 font-poppins">Generate and manage company reports</p>
				</div>
				<button onclick="showGenerateModal()" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>Generate Report
				</button>
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
			<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-blue-100 text-blue-600">
							<i class="fa fa-file-text text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Total Reports</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['total']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-green-100 text-green-600">
							<i class="fa fa-check text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Completed</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['completed']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
							<i class="fa fa-spinner text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Generating</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['generating']?></p>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-lg shadow-digitazio p-6">
					<div class="flex items-center">
						<div class="p-3 rounded-full bg-red-100 text-red-600">
							<i class="fa fa-times text-xl"></i>
						</div>
						<div class="ml-4">
							<p class="text-body-sm font-poppins text-gray-500">Failed</p>
							<p class="text-subheading font-poppins font-semibold text-eerie-black"><?=$counts['failed']?></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Filter Buttons -->
			<div class="mb-6 flex flex-wrap gap-2">
				<a href="reports.php?filter=all" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'all' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					All Reports
				</a>
				<a href="reports.php?filter=employee_month" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'employee_month' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Employee Monthly
				</a>
				<a href="reports.php?filter=employee_year" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'employee_year' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Employee Yearly
				</a>
				<a href="reports.php?filter=accounting" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'accounting' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Accounting
				</a>
				<a href="reports.php?filter=tasks" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'tasks' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Tasks
				</a>
				<a href="reports.php?filter=sales" class="px-4 py-2 rounded-lg font-poppins text-body font-medium transition-colors duration-300 <?=$filter == 'sales' ? 'bg-atlantis text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'?>">
					Sales
				</a>
			</div>

			<div class="bg-white rounded-lg shadow-digitazio overflow-hidden">
				<?php if ($reports != 0) { ?>
				<div class="overflow-x-auto">
					<table class="w-full">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Report Title</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Type</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Period</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Status</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Generated</th>
								<th class="px-6 py-4 text-left text-subheading-sm font-poppins font-semibold text-eerie-black">Actions</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200">
							<?php foreach ($reports as $report) { 
								// Status badge colors
								$statusColors = [
									'completed' => 'bg-green-100 text-green-800',
									'generating' => 'bg-yellow-100 text-yellow-800',
									'failed' => 'bg-red-100 text-red-800'
								];
								
								// Type badge colors
								$typeColors = [
									'employee_month' => 'bg-blue-100 text-blue-800',
									'employee_year' => 'bg-purple-100 text-purple-800',
									'accounting' => 'bg-green-100 text-green-800',
									'tasks' => 'bg-orange-100 text-orange-800',
									'sales' => 'bg-pink-100 text-pink-800'
								];
							?>
							<tr class="hover:bg-gray-50 transition-colors duration-200">
								<td class="px-6 py-4">
									<div class="font-poppins text-body font-medium text-eerie-black"><?=$report['title']?></div>
									<?php if ($report['data_summary']) { ?>
									<div class="font-poppins text-body-sm text-gray-500 mt-1"><?=$report['data_summary']?></div>
									<?php } ?>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium <?=$typeColors[$report['type']]?> capitalize">
										<?=str_replace('_', ' ', $report['type'])?>
									</span>
								</td>
								<td class="px-6 py-4 font-poppins text-body text-gray-700">
									<?=$report['period']?>
								</td>
								<td class="px-6 py-4">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-body-sm font-medium <?=$statusColors[$report['status']]?> capitalize">
										<?=$report['status']?>
									</span>
								</td>
								<td class="px-6 py-4 font-poppins text-body text-gray-500">
									<?=date('M d, Y', strtotime($report['created_at']))?>
								</td>
								<td class="px-6 py-4">
									<div class="flex gap-2">
										<?php if ($report['status'] == 'completed') { ?>
										<a href="app/download-report.php?id=<?=$report['id']?>" class="text-atlantis hover:text-atlantis-dark font-poppins text-body font-medium">
											<i class="fa fa-download mr-1"></i>Download
										</a>
										<?php } ?>
										<button onclick="deleteReport(<?=$report['id']?>)" class="text-red-600 hover:text-red-800 font-poppins text-body font-medium">
											<i class="fa fa-trash mr-1"></i>Delete
										</button>
									</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php } else { ?>
				<div class="text-center py-12">
					<i class="fa fa-file-text text-6xl text-gray-300 mb-4"></i>
					<h3 class="text-subheading font-poppins font-semibold text-gray-500 mb-2">No Reports Found</h3>
					<p class="text-body text-gray-400 font-poppins mb-6">No reports match the current filter.</p>
					<button onclick="showGenerateModal()" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300">
						<i class="fa fa-plus mr-2"></i>Generate Your First Report
					</button>
				</div>
				<?php } ?>
			</div>
		</section>
	</div>

	<!-- Generate Report Modal -->
	<div id="generateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
		<div class="flex items-center justify-center min-h-screen p-4">
			<div class="bg-white rounded-lg shadow-digitazio max-w-lg w-full">
				<div class="p-6">
					<div class="flex justify-between items-center mb-4">
						<h3 class="text-subheading font-poppins font-semibold text-eerie-black">Generate New Report</h3>
						<button onclick="closeGenerateModal()" class="text-gray-400 hover:text-gray-600">
							<i class="fa fa-times text-xl"></i>
						</button>
					</div>
					<form id="generateForm" class="space-y-4">
						<div>
							<label class="block text-body-sm font-poppins font-semibold text-gray-700 mb-2">Report Type</label>
							<select name="type" id="reportType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
								<option value="">Select Report Type</option>
								<option value="employee_month">Employee of the Month</option>
								<option value="employee_year">Employee of the Year</option>
								<option value="accounting">Accounting Report</option>
								<option value="tasks">Tasks Report</option>
								<option value="sales">Sales Report</option>
							</select>
						</div>

						<div class="grid grid-cols-2 gap-4">
							<div>
								<label class="block text-body-sm font-poppins font-semibold text-gray-700 mb-2">Year</label>
								<select name="year" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300" required>
									<?php for($i = date('Y'); $i >= 2020; $i--) { ?>
									<option value="<?=$i?>" <?=$i == date('Y') ? 'selected' : ''?>><?=$i?></option>
									<?php } ?>
								</select>
							</div>

							<div id="monthField">
								<label class="block text-body-sm font-poppins font-semibold text-gray-700 mb-2">Month</label>
								<select name="month" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-atlantis focus:border-transparent font-poppins text-body transition-all duration-300">
									<option value="">Select Month</option>
									<?php 
									$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
									for($i = 1; $i <= 12; $i++) { ?>
									<option value="<?=$i?>" <?=$i == date('n') ? 'selected' : ''?>><?=$months[$i-1]?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="flex gap-4 pt-4">
							<button type="submit" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
								<i class="fa fa-cog mr-2"></i>Generate Report
							</button>
							<button type="button" onclick="closeGenerateModal()" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
								<i class="fa fa-times mr-2"></i>Cancel
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(9)");
	active.classList.add("active");

	function showGenerateModal() {
		document.getElementById('generateModal').classList.remove('hidden');
	}

	function closeGenerateModal() {
		document.getElementById('generateModal').classList.add('hidden');
	}

	function deleteReport(id) {
		if (confirm('Are you sure you want to delete this report?')) {
			window.location.href = 'app/delete-report.php?id=' + id;
		}
	}

	// Handle report type change
	document.getElementById('reportType').addEventListener('change', function() {
		const monthField = document.getElementById('monthField');
		const monthSelect = monthField.querySelector('select');
		
		if (this.value === 'employee_year') {
			monthField.style.display = 'none';
			monthSelect.required = false;
		} else {
			monthField.style.display = 'block';
			monthSelect.required = true;
		}
	});

	// Handle form submission
	document.getElementById('generateForm').addEventListener('submit', function(e) {
		e.preventDefault();
		
		const formData = new FormData(this);
		
		fetch('app/generate-report.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				closeGenerateModal();
				location.reload();
			} else {
				alert('Error generating report: ' + data.message);
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Error generating report');
		});
	});

	// Close modal when clicking outside
	document.getElementById('generateModal').addEventListener('click', function(e) {
		if (e.target === this) {
			closeGenerateModal();
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
