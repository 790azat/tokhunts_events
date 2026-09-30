// Lightweight confetti: a gentle ambient fall on [data-confetti] canvases and a burst helper.
const COLORS = ['#ff4f8b', '#ffc93c', '#1fbf8f', '#4cc9f0', '#9b5de5', '#ff8a3d'];
const reduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const piece = (w, h, burst) => ({
    x: burst ? burst.x : Math.random() * w,
    y: burst ? burst.y : Math.random() * -h,
    vx: burst ? (Math.random() - 0.5) * 14 : (Math.random() - 0.5) * 0.6,
    vy: burst ? -Math.random() * 12 - 4 : Math.random() * 0.9 + 0.5,
    size: Math.random() * 7 + 5,
    rot: Math.random() * Math.PI,
    spin: (Math.random() - 0.5) * 0.2,
    color: COLORS[(Math.random() * COLORS.length) | 0],
    round: Math.random() < 0.3,
    life: burst ? 1 : Infinity,
});

const draw = (ctx, p) => {
    ctx.save();
    ctx.translate(p.x, p.y);
    ctx.rotate(p.rot);
    ctx.globalAlpha = Math.max(0, Math.min(1, p.life));
    ctx.fillStyle = p.color;
    if (p.round) {
        ctx.beginPath();
        ctx.arc(0, 0, p.size / 2.4, 0, Math.PI * 2);
        ctx.fill();
    } else {
        ctx.fillRect(-p.size / 2, -p.size / 4, p.size, p.size / 2);
    }
    ctx.restore();
};

function ambient(canvas) {
    if (canvas.dataset.running) return;
    canvas.dataset.running = '1';
    const ctx = canvas.getContext('2d');
    let pieces = [];
    let visible = true;
    const resize = () => {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = canvas.offsetWidth * dpr;
        canvas.height = canvas.offsetHeight * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        const count = Math.round(Math.min(70, canvas.offsetWidth / 18));
        pieces = Array.from({ length: count }, () => piece(canvas.offsetWidth, canvas.offsetHeight));
    };
    resize();
    window.addEventListener('resize', resize);
    new IntersectionObserver(([entry]) => (visible = entry.isIntersecting)).observe(canvas);

    const tick = () => {
        if (!canvas.isConnected) return;
        if (visible) {
            const w = canvas.offsetWidth;
            const h = canvas.offsetHeight;
            ctx.clearRect(0, 0, w, h);
            for (const p of pieces) {
                p.x += p.vx + Math.sin(p.y / 40) * 0.3;
                p.y += p.vy;
                p.rot += p.spin;
                if (p.y > h + 20) Object.assign(p, piece(w, h), { y: -20 });
                draw(ctx, p);
            }
        }
        requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

let layer;
export function burst(x = window.innerWidth / 2, y = window.innerHeight / 2, count = 120) {
    if (reduced()) return;
    if (!layer || !layer.isConnected) {
        layer = document.createElement('canvas');
        layer.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:200';
        document.body.appendChild(layer);
    }
    const ctx = layer.getContext('2d');
    layer.width = window.innerWidth;
    layer.height = window.innerHeight;
    let pieces = Array.from({ length: count }, () => piece(0, 0, { x, y }));
    const tick = () => {
        ctx.clearRect(0, 0, layer.width, layer.height);
        pieces = pieces.filter((p) => p.life > 0);
        for (const p of pieces) {
            p.vy += 0.35;
            p.vx *= 0.98;
            p.x += p.vx;
            p.y += p.vy;
            p.rot += p.spin * 2;
            p.life -= 0.008;
            draw(ctx, p);
        }
        if (pieces.length) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

export function initConfetti() {
    if (reduced()) return;
    document.querySelectorAll('canvas[data-confetti]').forEach(ambient);
}

document.addEventListener('click', (event) => {
    const el = event.target.closest('[data-confetti-burst]');
    if (el) burst(event.clientX, event.clientY, 80);
});

window.confettiBurst = burst;
