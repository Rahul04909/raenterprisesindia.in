document.addEventListener('DOMContentLoaded', function () {
    // Menu Dropdown Logic
    const menuLinks = document.querySelectorAll('.sidebar-menu > li > a');

    menuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const parentLi = this.parentElement;

            // Check if this item has a submenu
            if (parentLi.classList.contains('has-submenu')) {
                e.preventDefault(); // Prevent default link behavior if it's a dropdown toggle

                const submenu = parentLi.querySelector('.submenu');

                // Close other open submenus (WordPress style - accordion behavior)
                // Optional: If you want to allow multiple open, remove this block
                const otherOpenLis = document.querySelectorAll('.has-submenu.open');
                otherOpenLis.forEach(otherLi => {
                    if (otherLi !== parentLi) {
                        otherLi.classList.remove('open');
                        otherLi.querySelector('.submenu').style.display = 'none';
                    }
                });

                // Toggle current
                if (parentLi.classList.contains('open')) {
                    parentLi.classList.remove('open');
                    submenu.style.display = 'none';
                } else {
                    parentLi.classList.add('open');
                    submenu.style.display = 'block';
                }
            }
        });
    });

    // Sidebar Collapse Logic
    const collapseBtn = document.getElementById('collapse-btn');
    const sidebar = document.getElementById('admin-sidebar');
    const content = document.getElementById('admin-content');
    const collapseIcon = collapseBtn.querySelector('i');

    if (collapseBtn) {
        collapseBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');

            if (content) {
                content.classList.toggle('expanded');
            }

            // Change icon
            if (sidebar.classList.contains('collapsed')) {
                collapseIcon.classList.remove('fa-chevron-circle-left');
                collapseIcon.classList.add('fa-chevron-circle-right');
            } else {
                collapseIcon.classList.remove('fa-chevron-circle-right');
                collapseIcon.classList.add('fa-chevron-circle-left');
            }
        });
    }

    // Auto-expand sidebar if hovering while collapsed (Optional enhancement)
    // sidebar.addEventListener('mouseenter', function() {
    //     if (sidebar.classList.contains('collapsed')) {
    //         sidebar.classList.remove('collapsed');
    //         content.classList.remove('expanded');
    //     }
    // });
});
