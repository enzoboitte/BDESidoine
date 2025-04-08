function lerp(a, b, t) {
    return a + (b - a) * t;
}

function smoothScrollTo(targetY, duration = 600) {
    const startY = window.scrollY;
    const startTime = performance.now();

    function animateScroll(now) {
        const elapsed = now - startTime;
        const t = Math.min(elapsed / duration, 1);
        const easedT = t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; // easeInOutQuad
        const currentY = lerp(startY, targetY, easedT);

        window.scrollTo(0, currentY);

        if (t < 1) requestAnimationFrame(animateScroll);
    }

    requestAnimationFrame(animateScroll);
}


const btnUpsideDown = document.querySelector('#btn_aboutmore');
const target = document.querySelector('#menu');

btnUpsideDown.addEventListener('click', function(event) {
    event.preventDefault();
    const targetY = target.getBoundingClientRect().top + window.scrollY;
    smoothScrollTo(targetY);
});