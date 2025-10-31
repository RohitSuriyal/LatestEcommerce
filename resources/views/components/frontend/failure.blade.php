@if (session('failure'))
<div class="custom-alert error-alert">
    <strong>❌ Failed!</strong> {{ session('failure') }}
</div>
@endif

<style>
    .custom-alert {
        position: fixed;
        top: 30px;
        right: 30px;
        padding: 16px 24px;
        border-radius: 8px;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-weight: 500;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        animation: fadeIn 0.4s forwards, fadeOut 0.6s 3.4s forwards;
    }

    .error-alert {
        background-color: #dc3545;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }
</style>

<script>
    setTimeout(() => {
        document.querySelectorAll('.custom-alert').forEach(el => el.remove());
    }, 4000);
</script>
