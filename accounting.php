<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Accounting.php";
    
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    if($category == 'all') {
        $entries = get_all_accounting_entries($conn);
        $page_title = "All Financial Records";
    } else {
        $entries = get_accounting_entries_by_category($conn, $category);
        $page_title = ucfirst($category) . " Records";
    }
    
    $summary = get_financial_summary($conn);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Accounting Dashboard | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black mb-6">Accounting Dashboard</h1>
				
				<!-- Financial Summary Cards -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
					<div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-green-600 font-poppins text-body font-medium">Total Assets</p>
								<p class="text-2xl font-poppins font-bold text-green-800">PKR <?=number_format($summary['total_assets'], 2)?></p>
							</div>
							<i class="fa fa-building text-3xl text-green-500"></i>
						</div>
					</div>
					
					<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-blue-600 font-poppins text-body font-medium">Total Revenue</p>
								<p class="text-2xl font-poppins font-bold text-blue-800">PKR <?=number_format($summary['total_revenue'], 2)?></p>
							</div>
							<i class="fa fa-arrow-up text-3xl text-blue-500"></i>
						</div>
					</div>
					
					<div class="bg-red-50 border border-red-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-red-600 font-poppins text-body font-medium">Total Expenses</p>
								<p class="text-2xl font-poppins font-bold text-red-800">PKR <?=number_format($summary['total_expenses'] + $summary['total_salaries'], 2)?></p>
							</div>
							<i class="fa fa-arrow-down text-3xl text-red-500"></i>
						</div>
					</div>
					
					<div class="bg-<?=$summary['profit_loss'] >= 0 ? 'green' : 'red'?>-50 border border-<?=$summary['profit_loss'] >= 0 ? 'green' : 'red'?>-200 rounded-lg p-6 shadow-digitazio">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-<?=$summary['profit_loss'] >= 0 ? 'green' : 'red'?>-600 font-poppins text-body font-medium">Net Profit/Loss</p>
								<p class="text-2xl font-poppins font-bold text-<?=$summary['profit_loss'] >= 0 ? 'green' : 'red'?>-800">
									<?=$summary['profit_loss'] >= 0 ? '+' : ''?>PKR <?=number_format($summary['profit_loss'], 2)?>
								</p>
							</div>
							<i class="fa fa-<?=$summary['profit_loss'] >= 0 ? 'line-chart' : 'exclamation-triangle'?> text-3xl text-<?=$summary['profit_loss'] >= 0 ? 'green' : 'red'?>-500"></i>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="flex flex-wrap gap-3 mb-6">
					<a href="add-accounting.php" class="bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-2 px-4 rounded-lg transition-colors duration-300 shadow-digitazio">
						<i class="fa fa-plus mr-2"></i>Add Entry
					</a>
					<a href="accounting.php?category=assets" class="bg-green-500 hover:bg-green-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Assets
					</a>
					<a href="accounting.php?category=revenue" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Revenue
					</a>
					<a href="accounting.php?category=expenses" class="bg-red-500 hover:bg-red-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Expenses
					</a>
					<a href="accounting.php?category=salaries" class="bg-purple-500 hover:bg-purple-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Salaries
					</a>
					<a href="accounting.php?category=shares" class="bg-orange-500 hover:bg-orange-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						Shares
					</a>
					<a href="accounting.php" class="bg-gray-500 hover:bg-gray-600 text-white font-poppins py-2 px-4 rounded-lg transition-colors duration-300">
						All Records
					</a>
				</div>
				
				<h2 class="text-subheading font-poppins font-semibold text-eerie-black"><?=$page_title?></h2>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if ($entries != 0) { ?>
			<div class="overflow-x-auto bg-white rounded-lg shadow-digitazio">
				<table class="w-full table-auto">
					<thead class="bg-atlantis text-white">
						<tr>
							<th class="px-4 py-3 text-left font-poppins font-semibold">#</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Category</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Type</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Description</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Amount</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Date</th>
							<th class="px-4 py-3 text-left font-poppins font-semibold">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($entries as $entry) { ?>
						<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
							<td class="px-4 py-3 font-poppins text-body"><?=++$i?></td>
							<td class="px-4 py-3">
								<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
									<?php 
									switch($entry['category']) {
										case 'assets': echo 'bg-green-100 text-green-800'; break;
										case 'revenue': echo 'bg-blue-100 text-blue-800'; break;
										case 'expenses': echo 'bg-red-100 text-red-800'; break;
										case 'salaries': echo 'bg-purple-100 text-purple-800'; break;
										case 'shares': echo 'bg-orange-100 text-orange-800'; break;
									}
									?>">
									<?=ucfirst($entry['category'])?>
								</span>
							</td>
							<td class="px-4 py-3 font-poppins text-body font-medium"><?=$entry['type']?></td>
							<td class="px-4 py-3 font-poppins text-body"><?=$entry['description']?></td>
							<td class="px-4 py-3 font-poppins text-body font-bold text-<?=$entry['category'] == 'revenue' ? 'green' : 'red'?>-600">
								<?=$entry['category'] == 'revenue' ? '+' : '-'?>PKR <?=number_format($entry['amount'], 2)?>
							</td>
							<td class="px-4 py-3 font-poppins text-body"><?=date('M d, Y', strtotime($entry['date']))?></td>
							<td class="px-4 py-3">
								<div class="flex space-x-2">
									<a href="edit-accounting.php?id=<?=$entry['id']?>" class="bg-blue-500 hover:bg-blue-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300">
										Edit
									</a>
									<a href="delete-accounting.php?id=<?=$entry['id']?>" class="bg-red-500 hover:bg-red-600 text-white font-poppins text-xs py-1 px-3 rounded transition-colors duration-300" onclick="return confirm('Are you sure you want to delete this entry?')">
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
				<i class="fa fa-calculator text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No accounting records found</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">Start by adding your first financial entry!</p>
				<a href="add-accounting.php" class="inline-block mt-4 bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
					<i class="fa fa-plus mr-2"></i>Add Entry
				</a>
			</div>
			<?php } ?>
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
