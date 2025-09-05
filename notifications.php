<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Notification.php";
    // include "app/Model/User.php";

    $notifications = get_all_my_notifications($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Notifications | Digitazio Panel</title>
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
				<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Notifications</h1>
				<p class="text-body font-poppins text-gray-600 mt-2">Stay updated with your latest notifications</p>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 font-poppins text-body">
				<i class="fa fa-check-circle mr-2"></i><?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if ($notifications != 0) { ?>
			<div class="space-y-4">
				<?php $i=0; foreach ($notifications as $notification) { ?>
				<div class="bg-white rounded-lg shadow-digitazio p-6 border-l-4 
					<?php 
					if($notification['type'] == 'success') echo 'border-green-500';
					elseif($notification['type'] == 'warning') echo 'border-yellow-500';
					elseif($notification['type'] == 'error') echo 'border-red-500';
					else echo 'border-atlantis';
					?>">
					<div class="flex items-start justify-between">
						<div class="flex items-start space-x-4">
							<div class="flex-shrink-0">
								<div class="w-10 h-10 rounded-full flex items-center justify-center
									<?php 
									if($notification['type'] == 'success') echo 'bg-green-100';
									elseif($notification['type'] == 'warning') echo 'bg-yellow-100';
									elseif($notification['type'] == 'error') echo 'bg-red-100';
									else echo 'bg-atlantis bg-opacity-20';
									?>">
									<i class="fa 
										<?php 
										if($notification['type'] == 'success') echo 'fa-check-circle text-green-600';
										elseif($notification['type'] == 'warning') echo 'fa-exclamation-triangle text-yellow-600';
										elseif($notification['type'] == 'error') echo 'fa-times-circle text-red-600';
										else echo 'fa-bell text-atlantis';
										?>"></i>
								</div>
							</div>
							<div class="flex-1">
								<div class="flex items-center space-x-2 mb-2">
									<span class="px-2 py-1 rounded-full text-xs font-poppins font-medium
										<?php 
										if($notification['type'] == 'success') echo 'bg-green-100 text-green-800';
										elseif($notification['type'] == 'warning') echo 'bg-yellow-100 text-yellow-800';
										elseif($notification['type'] == 'error') echo 'bg-red-100 text-red-800';
										else echo 'bg-atlantis bg-opacity-20 text-atlantis';
										?>">
										<?=ucfirst($notification['type'])?>
									</span>
									<span class="text-body-sm font-poppins text-gray-500">
										<?=date('M j, Y \a\t g:i A', strtotime($notification['date']))?>
									</span>
								</div>
								<p class="text-body font-poppins text-eerie-black leading-relaxed">
									<?=$notification['message']?>
								</p>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<?php }else { ?>
			<div class="text-center py-12">
				<i class="fa fa-bell-slash text-6xl text-gray-300 mb-4"></i>
				<h3 class="text-subheading font-poppins font-semibold text-gray-500">No notifications</h3>
				<p class="text-body font-poppins text-gray-400 mt-2">You're all caught up! No new notifications at this time.</p>
			</div>
			<?php }?>
			
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