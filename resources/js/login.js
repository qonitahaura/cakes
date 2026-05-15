const form = document.getElementById('cakes-login-form');
const errorText = document.getElementById('cakes-login-error');

if (form) {

    form.addEventListener('submit', async (e) => {

        e.preventDefault();

        const formData = new FormData(form);

        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        });

        // THROTTLE
        if (response.status === 429) {

    const data = await response.json();

    const seconds = parseInt(data.retry_after);

    if (!isNaN(seconds)) {
        startCountdown(seconds);
    } else {
        errorText.classList.remove('hidden');

        errorText.textContent =
            'Terlalu banyak percobaan login';
    }

    return;
}

        const data = await response.json();

        // LOGIN GAGAL
        if (!response.ok) {

            errorText.classList.remove('hidden');

            errorText.textContent =
                data.message || 'Login gagal';

            return;
        }

    });
}

function startCountdown(seconds) {

    errorText.classList.remove('hidden');

    const interval = setInterval(() => {

        errorText.textContent =
            `Terlalu banyak percobaan login. Coba lagi dalam ${seconds} detik`;

        seconds--;

        if (seconds < 0) {

            clearInterval(interval);

            errorText.textContent = '';

            errorText.classList.add('hidden');
        }

    }, 1000);
}