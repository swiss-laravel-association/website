// ASCII plasma + Bayer-dithered orange dividers.
// Initialised from any page that contains the matching canvas elements.

const CHAR_RAMP = ' .·:;+=*#%@';
const FONT_MONO = "'IBM Plex Mono', monospace";
const FONT_SIZE = 12;
const DPR = window.devicePixelRatio || 1;

const BAYER4 = [
    [ 0,  8,  2, 10],
    [12,  4, 14,  6],
    [ 3, 11,  1,  9],
    [15,  7, 13,  5],
];

// ── Plasma field functions ────────────────────────────────────────────────
function plasmaAurora(col, row, t) {
    const nx = col / 8;
    const ny = row / 6;
    const curtain = Math.sin(nx * 0.7 + t * 0.4) * Math.cos(ny * 1.2 - t * 0.2);
    const shimmer = Math.sin(nx * 1.5 + ny * 0.5 + t * 0.8) * 0.3;
    const vertical = Math.cos(ny * 2.0 - t * 0.15) * 0.4;
    return (curtain + shimmer + vertical + 1.7) / 3.4;
}

function plasmaClassic(col, row, t) {
    const nx = col / 10;
    const ny = row / 10;
    const a = Math.sin(nx + t * 0.5);
    const b = Math.sin(ny + t * 0.3);
    const c = Math.sin((nx + ny + t) * 0.5);
    const d = Math.sin(Math.sqrt(nx * nx + ny * ny) - t * 0.4);
    return (a + b + c + d + 4) / 8;
}

// ── Generic ASCII canvas animator ─────────────────────────────────────────
function createAsciiAnimation({ canvas, getSize, plasmaFn, colorFn, rowCount, speed }) {
    const ctx = canvas.getContext('2d');
    let time = 0;
    let charW = 0;
    let charH = 0;
    let cols = 0;
    let animId = null;

    function resize() {
        const { w, h } = getSize();
        canvas.style.width  = `${w}px`;
        canvas.style.height = `${h}px`;
        canvas.width  = Math.round(w * DPR);
        canvas.height = Math.round(h * DPR);
        ctx.setTransform(DPR, 0, 0, DPR, 0, 0);

        ctx.font = `${FONT_SIZE}px ${FONT_MONO}`;
        charW = ctx.measureText('@').width || FONT_SIZE * 0.6;
        charH = FONT_SIZE * 1.35;
        cols  = Math.max(1, Math.floor(w / charW));
    }

    function draw() {
        time += speed * 0.016;

        const w = canvas.width / DPR;
        const h = canvas.height / DPR;

        ctx.clearRect(0, 0, w, h);
        ctx.font = `${FONT_SIZE}px ${FONT_MONO}`;
        ctx.textBaseline = 'top';

        for (let row = 0; row < rowCount; row++) {
            for (let col = 0; col < cols; col++) {
                const v  = Math.max(0, Math.min(1, plasmaFn(col, row, time)));
                const ci = Math.floor(v * (CHAR_RAMP.length - 1));
                const ch = CHAR_RAMP[ci];
                if (ch === ' ') continue;
                ctx.fillStyle = colorFn(v);
                ctx.fillText(ch, col * charW, row * charH);
            }
        }

        animId = requestAnimationFrame(draw);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                if (!animId) { resize(); animId = requestAnimationFrame(draw); }
            } else if (animId) {
                cancelAnimationFrame(animId);
                animId = null;
            }
        });
    }, { threshold: 0 });

    observer.observe(canvas);
    window.addEventListener('resize', resize, { passive: true });
    resize();
}

// ── Bayer-dithered orange gradient stripe ─────────────────────────────────
function createDitherDivider({ canvas, height, speed }) {
    const ctx = canvas.getContext('2d');
    const PXSIZE = 4;
    let time = 0;
    let w = 0;
    let cols = 0;
    let rows = 0;
    let animId = null;

    function resize() {
        w = window.innerWidth;
        canvas.style.width  = `${w}px`;
        canvas.style.height = `${height}px`;
        canvas.width  = Math.round(w * DPR);
        canvas.height = Math.round(height * DPR);
        ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
        cols = Math.ceil(w / PXSIZE);
        rows = Math.ceil(height / PXSIZE);
    }

    function draw() {
        time += speed;

        ctx.clearRect(0, 0, w, height);

        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols; c++) {
                const nx = c / cols;
                const gradient = 0.5 + 0.5 * Math.sin(nx * Math.PI * 2 - time);
                const threshold = BAYER4[r % 4][c % 4] / 16;

                if (gradient > threshold) {
                    const alpha = 0.10 + gradient * 0.20;
                    ctx.fillStyle = `rgba(249,115,22,${alpha.toFixed(3)})`;
                    ctx.fillRect(c * PXSIZE, r * PXSIZE, PXSIZE, PXSIZE);
                }
            }
        }

        animId = requestAnimationFrame(draw);
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                if (!animId) { resize(); animId = requestAnimationFrame(draw); }
            } else if (animId) {
                cancelAnimationFrame(animId);
                animId = null;
            }
        });
    }, { threshold: 0 });

    observer.observe(canvas);
    window.addEventListener('resize', resize, { passive: true });
    resize();
}

// ── Boot ──────────────────────────────────────────────────────────────────
function init() {
    const hero = document.getElementById('heroAsciiCanvas');
    if (hero) {
        const wrap = hero.parentElement;
        createAsciiAnimation({
            canvas: hero,
            getSize: () => wrap
                ? { w: wrap.offsetWidth, h: wrap.offsetHeight }
                : { w: 500, h: 340 },
            plasmaFn: plasmaAurora,
            colorFn: (v) => `rgba(249,115,22,${(0.3 + v * 0.7).toFixed(3)})`,
            rowCount: 24,
            speed: 0.6,
        });
    }

    const footer = document.getElementById('footerAsciiCanvas');
    if (footer) {
        createAsciiAnimation({
            canvas: footer,
            getSize: () => ({ w: window.innerWidth, h: 80 }),
            plasmaFn: plasmaClassic,
            colorFn: (v) => `rgba(181,178,188,${(0.12 + v * 0.40).toFixed(3)})`,
            rowCount: 5,
            speed: 0.5,
        });
    }

    document.querySelectorAll('canvas[data-dither-divider]').forEach((canvas) => {
        createDitherDivider({
            canvas,
            height: Number(canvas.dataset.ditherHeight) || 28,
            speed: Number(canvas.dataset.ditherSpeed) || 0.01,
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
