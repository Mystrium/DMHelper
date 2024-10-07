<div id="alert-container">
    @if($errors->any())
        <div id="customAlert" style="z-index: 1030" class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x" role="alert" style="width: auto;">
                {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<script>
    setTimeout(function() {
        let alertElement = document.getElementById('customAlert');
        if(alertElement != null){
            let alert = new bootstrap.Alert(alertElement);
            alert.close();
        }
    }, 2000);

    function showAlert(message, type = 'success') {
        const alert = document.createElement('div');
        alert.style = "z-index: 1030";
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x`;
        alert.role = 'alert';
        alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

        const container = document.getElementById('alert-container');
        container.appendChild(alert);

        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => { container.removeChild(alert); }, 150);
        }, 2000);
    }
</script>