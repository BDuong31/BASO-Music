<?php declare(strict_types=1);
function renderCircle($color, $percentage, $size, $strokeWidth) {
    $radius = $size / 2 - 10;
    $circ = 2 * pi() * $radius - 20;
    $strokePct = ((100 - round($percentage)) * $circ) / 100;
    $strokeColor = $percentage > 0 ? $color : "none";

    echo '
    <circle
        r="' . $radius . '"
        cx="50%"
        cy="50%"
        fill="transparent"
        stroke="' . $strokeColor . '"
        stroke-width="' . $strokeWidth . '"
        stroke-dasharray="' . $circ . '"
        stroke-dashoffset="' . ($percentage ? $strokePct : 0) . '"
        stroke-linecap="round"
    ></circle>';
}

function renderProgressCircle($percentage, $isPlaying, $size, $color, $image) {
    $activeClass = $isPlaying ? 'active' : '';
    $circle1Size = $size / 2 - 30;
    $circle2Size = $size / 2 - 100;

    echo '
    <div class="progress-circle flex">
        <svg width="' . $size . '" height="' . $size . '">
            <g>';
                renderCircle("#3B4F73", 100, $size, "0.4rem");
                renderCircle($color, $percentage, $size, "0.6rem");
    echo '
            </g>
            <defs>
                <clipPath id="myCircle">
                    <circle cx="50%" cy="50%" r="' . $circle1Size . '" fill="#FFFFFF" />
                </clipPath>
                <clipPath id="myInnerCircle">
                    <circle cx="50%" cy="50%" r="' . $circle2Size . '" fill="#FFFFFF" />
                </clipPath>
            </defs>
            <image
                class="' . $activeClass . '"
                x="30"
                y="30"
                width="' . (2 * $circle1Size) . '"
                height="' . (2 * $circle1Size) . '"
                href="https://pngimg.com/uploads/vinyl/vinyl_PNG107.png"
                clip-path="url(#myCircle)"
            />
            <image
                class="' . $activeClass . '"
                x="100"
                y="100"
                width="' . (2 * $circle2Size) . '"
                height="' . (2 * $circle2Size) . '"
                href="' . $image . '"
                clip-path="url(#myInnerCircle)"
            />
        </svg>
    </div>';
}
?>