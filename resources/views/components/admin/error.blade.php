@if(session("error"))
    <div id="error-alert" class="alert alert-danger" role="alert">
        {{session("error")}}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


<script>
    // Hide after 3 seconds
    setTimeout(function () {
        var alert = document.getElementById('error-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 3000); // 3000ms = 3s
</script>