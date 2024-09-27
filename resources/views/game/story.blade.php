@extends('layouts.layout')

@section('title', 'Мапа світу')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center text-center">
            <div class="col-md-8 mb-5 border rounded history-block active-block">
                <h5>Назва блоку</h5> 
                <textarea class="form-control mb-3" rows="4">Цей текст можна редагувати. Це перший блок історії.</textarea>
            </div>
            
            <div class="col-md-8 mb-5 border rounded history-block">
                <h5>bbbbbbbbbbbbbbbbbbbbbbbbb</h5>
                <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це другий блок історії.</textarea>
            </div>

            <div class="col-md-8 mb-5 border rounded history-block">
                <h5>cccccccccccccccccccccccc</h5>
                <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
            </div>

            <div class="col-md-8 mb-5 border rounded history-block">
                <ul class="nav nav-tabs" id="storyTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="variant1-tab" data-bs-toggle="tab" data-bs-target="#variant1" type="button" role="tab" aria-controls="variant1" aria-selected="true">Варіант 1</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="variant2-tab" data-bs-toggle="tab" data-bs-target="#variant2" type="button" role="tab" aria-controls="variant2" aria-selected="false">Варіант 2</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="variant3-tab" data-bs-toggle="tab" data-bs-target="#variant3" type="button" role="tab" aria-controls="variant3" aria-selected="false">Варіант 3</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="storyTabsContent">
                    <div class="tab-pane fade show active" id="variant1" role="tabpanel" aria-labelledby="variant1-tab">
                        <div class="mb-5">
                            <h5>cccccccccccccccccccccccc</h5>
                            <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="variant2" role="tabpanel" aria-labelledby="variant2-tab">
                        <div class="mb-5">
                            <h5>cccccccccccccccccccccccc</h5>
                            <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="variant3" role="tabpanel" aria-labelledby="variant3-tab">
                        <div class="mb-5">
                            <h5>cccccccccccccccccccccccc</h5>
                            <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-5 border rounded history-block">
                <h5>cccccccccccccccccccccccc</h5>
                <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
            </div>

            <div class="col-md-8 mb-5 border rounded history-block">
                <h5>cccccccccccccccccccccccc</h5>
                <textarea class="form-control mb-3" rows="4">Цей текст також можна редагувати. Це третій блок історії.</textarea>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.history-block ').forEach(textarea => {
    textarea.addEventListener('click', (event) => {
        document.querySelectorAll('.history-block').forEach(block => {
            block.classList.remove('active-block');
            block.classList.add('inactive-block');
        });

        const parentBlock = event.target.closest('.history-block');
        parentBlock.classList.add('active-block');
        parentBlock.classList.remove('inactive-block');

        window.scrollTo({ top: parentBlock.offsetTop - 250, behavior: 'smooth'});
    });
});
</script>
@endsection