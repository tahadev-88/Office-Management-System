<header class="flex justify-between items-center px-8 py-4 bg-eerie-black text-white shadow-digitazio">
	<h2 class="text-subheading font-poppins font-semibold text-white flex items-center">
		<span class="text-white">Digitazio</span>
		<span class="text-atlantis ml-2">Panel</span>
		<input type="checkbox" id="checkbox" class="hidden">
		<label for="checkbox" class="ml-16 cursor-pointer">
			<i id="navbtn" class="fa fa-bars text-xl text-white hover:text-atlantis transition-colors duration-300" aria-hidden="true"></i>
		</label>
	</h2>
	<div class="notification relative cursor-pointer" id="notificationBtn">
		<i class="fa fa-bell text-2xl text-white hover:text-atlantis transition-colors duration-300" aria-hidden="true"></i>
		<span id="notificationNum" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center"></span>
	</div>
</header>
<div class="notification-bar hidden w-11/12 max-w-sm absolute right-0 bg-white p-2 border border-gray-300 shadow-digitazio z-50" id="notificationBar">
	<ul id="notifications" class="space-y-2">
	
	</ul>
</div>
<script type="text/javascript">
	var openNotification = false;

	const notification = ()=> {
		let notificationBar = document.querySelector("#notificationBar");
		if (openNotification) {
			notificationBar.classList.add('hidden');
			notificationBar.classList.remove('block');
			openNotification = false;
		}else {
			notificationBar.classList.remove('hidden');
			notificationBar.classList.add('block');
			openNotification = true;
		}
	}
	let notificationBtn = document.querySelector("#notificationBtn");
	notificationBtn.addEventListener("click", notification);
</script>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).ready(function(){

       $("#notificationNum").load("app/notification-count.php");
       $("#notifications").load("app/notification.php");

   });
</script>