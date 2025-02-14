            </div> <!-- Đóng div.content -->
        </div> <!-- Đóng div.d-flex -->

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Custom Admin JS -->
        <script src="assets/js/admin.js"></script>
        
        <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.content').classList.toggle('active');
        });

        // Highlight active menu item
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.sidebar .list-group-item').forEach(item => {
            if(item.getAttribute('href') === currentPage) {
                item.classList.add('active');
            }
        });
        </script>
    </body>
</html>
