@if($errors->any())
    <div id="customAlert" style="z-index: 1030" class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x" role="alert" style="width: auto;">
            {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    setTimeout(function() {
        let alertElement = document.getElementById('customAlert');
        let alert = new bootstrap.Alert(alertElement);
        alert.close();
    }, 2000);
</script>