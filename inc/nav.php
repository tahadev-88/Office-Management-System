<nav class="w-64 bg-gray-800 min-h-screen transition-all duration-500 ease-in-out" id="sidebar">
			<div class="text-center pt-6 pb-4 px-4 user-profile">
				<img src="img/user.png" class="w-20 h-20 rounded-full mx-auto mb-3">
				<h4 class="text-gray-300 font-poppins text-sm">@<?=$_SESSION['username']?></h4>
			</div>
			
			<?php 

               if($_SESSION['role'] == "employee"){
			 ?>
			 <!-- Employee Navigation Bar -->
			<ul id="navList" class="mt-4 space-y-1">
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="index.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-tachometer text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Dashboard</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="my_task.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-tasks text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">My Task</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="profile.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-user text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Profile</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="my_inventory.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-cube text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">My Inventory</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="my_requests.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-paper-plane text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">My Requests</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="notifications.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-bell text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Notifications</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="logout.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-sign-out text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Logout</span>
					</a>
				</li>
			</ul>
		<?php }else { ?>
			<!-- Admin Navigation Bar -->
            <ul id="navList" class="mt-4 space-y-1">
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="index.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-tachometer text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Dashboard</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="user.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-users text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Manage employees</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="credentials.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-key text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Employee Credentials</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="accounting.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-calculator text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Accounting</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="inventory.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-cube text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Inventory</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="create_task.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-plus text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Create Task</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="tasks.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-tasks text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">All Tasks</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="requests.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-inbox text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Employee Requests</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="reports.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-file-text text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Reports</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="clients.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-handshake-o text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Clients</span>
					</a>
				</li>
				<li class="px-4 py-3 hover:bg-atlantis transition-colors duration-300 cursor-pointer">
					<a href="logout.php" class="flex items-center text-gray-300 hover:text-white text-body font-poppins">
						<i class="fa fa-sign-out text-lg mr-3" aria-hidden="true"></i>
						<span class="nav-text">Logout</span>
					</a>
				</li>
			</ul>
		<?php } ?>
		</nav>