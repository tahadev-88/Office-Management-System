<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Digitazio Panel</title>
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
<body class="font-poppins bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">
      
      <div class="bg-white p-8 rounded-lg shadow-digitazio w-full max-w-md">
      	<div class="text-center mb-8">
      		<h1 class="text-heading-sm font-poppins font-semibold text-eerie-black">Digitazio</h1>
      		<h2 class="text-subheading font-poppins text-atlantis">Panel Login</h2>
      	</div>

      	<form method="POST" action="app/login.php" class="space-y-6">
      	  <?php if (isset($_GET['error'])) {?>
      	  	<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded font-poppins text-body">
		  <?php echo stripcslashes($_GET['error']); ?>
		</div>
      	  <?php } ?>

      	  <?php if (isset($_GET['success'])) {?>
      	  	<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded font-poppins text-body">
		  <?php echo stripcslashes($_GET['success']); ?>
		</div>
      	  <?php } ?>
  
		<div class="space-y-2">
		    <label for="user_name" class="block text-body font-poppins font-medium text-eerie-black">Username</label>
		    <input type="text" 
		    	   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body" 
		    	   name="user_name" 
		    	   id="user_name"
		    	   required>
		</div>
		<div class="space-y-2">
		    <label for="password" class="block text-body font-poppins font-medium text-eerie-black">Password</label>
		    <input type="password" 
		    	   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-atlantis focus:outline-none transition-colors duration-300 font-poppins text-body" 
		    	   name="password" 
		    	   id="password"
		    	   required>
		</div>
		<button type="submit" 
			class="w-full bg-atlantis hover:bg-atlantis-dark text-white font-poppins font-semibold py-3 px-6 rounded-lg transition-colors duration-300 shadow-digitazio">
			Login
		</button>
	</form>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>