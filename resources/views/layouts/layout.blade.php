
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .footer .nav-item { flex: 1; }

            .nav-tabs .nav-link.active {
                background-color: lightgray;
                color: white;
                border-color: black;
            }

            .active-block {
                background-color: #e9f7df; /* Світло-зелений фон */
                box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); /* Підсвітка */
                transform: scale(1.05); /* Трохи збільшений */
                opacity: 1; /* Повна прозорість */
                transition: all 0.3s ease-in-out; /* Анімація змін */
            }

            /* Неактивні блоки: менші, напівпрозорі */
            .inactive-block {
                background-color: #f8f9fa; /* Сірий фон */
                opacity: 0.5; /* Напівпрозорість */
                transform: scale(0.95); /* Трохи зменшений */
                transition: all 0.3s ease-in-out; /* Анімація змін */
            }

            .map-wrapper {
    width: 100%;
    height: 700px;
    overflow: hidden;
    position: relative;
    border: 2px solid #000;
}

/* Контейнер для перетягування та масштабування */
.map-container {
    width: 100%;
    height: 100%;
    cursor: grab;
    transition: transform 0.3s ease;
    transform-origin: top left;
}
        </style>
    </head>
    
    <body>
        <div class="pb-5"></div>
        @include('layouts.header')

        @yield('content')

        @include('layouts.footer')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
