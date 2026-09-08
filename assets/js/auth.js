document.querySelectorAll('.password-toggle').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.target);
        const icon = button.querySelector('i');

        if (!input) return;

        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';

        if (icon) {
            icon.classList.toggle('fa-eye', showing);
            icon.classList.toggle('fa-eye-slash', !showing);
        }

        button.setAttribute(
            'aria-label',
            showing ? 'Show password' : 'Hide password'
        );
    });
<<<<<<< HEAD
});
=======
});
>>>>>>> 8dc5399113603236efb726c8becb8ab1ef1509ec
