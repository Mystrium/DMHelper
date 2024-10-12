<style>
    .footer .nav-item { flex: 1; }

    .nav-tabs .nav-link.active {
        background-color: lightgray;
        color: white;
        border-color: black;
    }


    .active-block {
        background-color: #e9f7df;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        transform: scale(1.05);
        opacity: 1;
        transition: all 0.3s ease-in-out;
    }

    .inactive-block {
        background-color: #f8f9fa;
        opacity: 0.5;
        transform: scale(0.95);
        transition: all 0.3s ease-in-out;
    }


    .game-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, background-color 0.3s ease;
        background-size: cover;
        background-position: center;
        border-radius: 10px;
        height: 250px;
        color: white;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .game-card:hover {
        transform: scale(1.05);
        background-color: rgba(0, 0, 0, 0.7);
    }

    .game-card-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 10px;
    }

    .video-item {
        width: 350px;
        height:200px;
    }

    .map-wrapper {
        width: 100%;
        height: 650px;
        overflow: hidden;
        position: relative;
        border: 2px solid #000;
    }

    .map-container {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        cursor: grab;
    }

    .map-image {
        position: absolute;
        max-width: none;
        transform-origin: 0 0;
        transition: transform 0.1s ease;
    }

    #marker-container {
        position: absolute;
        max-width: none;
        transform-origin: top left;
        transition: transform 0.1s ease;
    }

    .map-marker {
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transform-origin: center bottom;
    }

    .tooltip-card {
        position: absolute;
        left: 15px;
        top: -5px;
        z-index: 50;
        display: none;
    }

    .map-marker:hover .tooltip-card { 
        display: inline-block;
        white-space: nowrap;
    }

    .crosshair {cursor: crosshair;}

    .node {
        position: absolute;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
    }

    .grid { display: grid; }

    .grid-cell {
        border: 1px solid #000;
        cursor: pointer;
    }

</style>