@if(session("success"))
<div id="success-alert" class="alert alert-success" role="alert">
    {{ session("success") }}
</div>

<script>
    // Hide after 3 seconds
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if(alert) {
            alert.style.display = 'none';
        }
    }, 3000); // 3000ms = 3s
</script>
@endif
