// Sidebar toggle functionality for Digitazio Panel
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('checkbox');
    const sidebar = document.getElementById('sidebar');
    const navBtn = document.getElementById('navbtn');
    
    // Toggle function
    function toggleSidebar() {
        if (sidebar) {
            const navTexts = sidebar.querySelectorAll('.nav-text');
            const userProfile = sidebar.querySelector('.user-profile');
            
            if (sidebar.classList.contains('sidebar-collapsed')) {
                // Expand sidebar
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.style.width = '16rem'; // w-64 equivalent
                
                // Show text elements
                navTexts.forEach(text => {
                    text.style.display = 'inline';
                    text.style.visibility = 'visible';
                });
                if (userProfile) {
                    userProfile.style.display = 'block';
                    userProfile.style.visibility = 'visible';
                }
            } else {
                // Collapse sidebar
                sidebar.classList.add('sidebar-collapsed');
                sidebar.style.width = '4rem'; // w-16 equivalent
                
                // Hide text elements
                navTexts.forEach(text => {
                    text.style.display = 'none';
                    text.style.visibility = 'hidden';
                });
                if (userProfile) {
                    userProfile.style.display = 'none';
                    userProfile.style.visibility = 'hidden';
                }
            }
        }
    }
    
    // Add click event to the navigation button
    if (navBtn) {
        navBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }
    
    // Also handle checkbox change for backward compatibility
    if (checkbox && sidebar) {
        checkbox.addEventListener('change', function() {
            toggleSidebar();
        });
    }
    
    // Active navigation highlighting
    const currentPage = window.location.pathname.split('/').pop();
    const navItems = document.querySelectorAll('#navList li');
    
    navItems.forEach(item => {
        const link = item.querySelector('a');
        if (link) {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === '' && href === 'index.php')) {
                item.classList.add('active-nav');
            }
        }
    });
});
