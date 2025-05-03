document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const inputs = form.querySelectorAll('input[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    const successMessage = new URLSearchParams(window.location.search).get('success');
    if (successMessage === '1') {
        alert('Registration successful!');
    }
});