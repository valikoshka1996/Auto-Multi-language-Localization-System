<?php require_once '../../lang_engine.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адаптивний Слайдер</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="slider-container">
    <div class="slider">
        <div class="slide">
            <img src="../../images/slide01.jpg" alt="Image 1" class="slide-image">
            <div class="content">
                <h2 class="small-text"><?= $texts['slider_1_1'] ?? 'Main text missing' ?></h2>
                <div class="divider"></div>
                <h1 class="large-text"><?= $texts['slider_1_2'] ?? 'Main text missing' ?></h1>
            </div>
        </div>
        <div class="slide">
            <img src="../../images/slide02.jpg" alt="Image 2" class="slide-image">
            <div class="content">
                <h2 class="small-text"><?= $texts['slider_2_1'] ?? 'Main text missing' ?></h2>
                <div class="divider"></div>
                <h1 class="large-text"><?= $texts['slider_2_2'] ?? 'Main text missing' ?></h1>
            </div>
        </div>
                <div class="slide">
            <img src="../../images/slide03.jpg" alt="Image 2" class="slide-image">
            <div class="content">
                <h2 class="small-text"><?= $texts['slider_3_1'] ?? 'Main text missing' ?></h2>
                <div class="divider"></div>
                <h1 class="large-text"><?= $texts['slider_3_2'] ?? 'Main text missing' ?></h1>
            </div>
        </div>
        </div>
        <!-- Додайте інші слайди за потребою -->
<div class="scroll-down-indicator">
    ↓
</div>
    </div>
<div class="slider-dots" id="sliderDots"></div>

</div>

    <script src="script.js"></script>
</body>
</html>
