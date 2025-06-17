document.getElementById('requestForm').addEventListener('submit', function(e) {
    const guests = document.getElementById('guests').value;
    const product = document.getElementById('product').value;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');

    // Validação básica no lado do cliente
    if (!guests || !product || !name || !email || !phone) {
        e.preventDefault();
        errorMessage.style.display = 'block';
        successMessage.style.display = 'none';
    }
});