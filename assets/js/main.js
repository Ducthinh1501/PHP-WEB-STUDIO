// Loading Spinner
function showLoading() {
    const loading = document.createElement('div');
    loading.className = 'loading';
    loading.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.querySelector('.loading');
    if (loading) {
        loading.remove();
    }
}

// Form Validation
function validateBookingForm() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const phone = form.querySelector('[name="soDienThoai"]').value;
        const phoneRegex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
        
        if (!phoneRegex.test(phone)) {
            e.preventDefault();
            alert('Số điện thoại không hợp lệ!');
        }
    });
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    validateBookingForm();
}); 