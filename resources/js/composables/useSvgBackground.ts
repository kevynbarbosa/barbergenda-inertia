import svg from '@/assets/pattern-randomized.svg?raw';
import { ref, onMounted, onUnmounted } from 'vue';

function cssColorToHex(cssColor: string): string {
    // Se já é hex, retorna
    if (cssColor.startsWith('#')) {
        return cssColor;
    }

    // Se é rgb() ou hsl(), usa canvas para converter
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d')!;
    ctx.fillStyle = cssColor;
    return ctx.fillStyle as string;
}

function hexToHsl(hex: string) {
    // Garantir que temos um hex válido
    const cleanHex = cssColorToHex(hex);
    const r = parseInt(cleanHex.slice(1, 3), 16) / 255;
    const g = parseInt(cleanHex.slice(3, 5), 16) / 255;
    const b = parseInt(cleanHex.slice(5, 7), 16) / 255;

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    let h = 0, s = 0;
    const l = (max + min) / 2;

    if (max !== min) {
        const d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

        switch (max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }

    return { h: h * 360, s: s * 100, l: l * 100 };
}

function hslToHex(h: number, s: number, l: number) {
    h /= 360; s /= 100; l /= 100;

    const hue2rgb = (p: number, q: number, t: number) => {
        if (t < 0) t += 1;
        if (t > 1) t -= 1;
        if (t < 1/6) return p + (q - p) * 6 * t;
        if (t < 1/2) return q;
        if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
        return p;
    };

    const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
    const p = 2 * l - q;

    const r = Math.round(hue2rgb(p, q, h + 1/3) * 255);
    const g = Math.round(hue2rgb(p, q, h) * 255);
    const b = Math.round(hue2rgb(p, q, h - 1/3) * 255);

    return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
}

function replaceSvgTokens(svgString: string, tokenMap: Record<string, string>) {
    let modifiedSvg = svgString;

    // Substituir tokens no SVG
    Object.entries(tokenMap).forEach(([token, color]) => {
        const regex = new RegExp(`{{${token}}}`, 'g');
        modifiedSvg = modifiedSvg.replace(regex, color);
    });

    return modifiedSvg;
}

function generateTokenColors(primaryColor: string, backgroundColor: string) {
    const primaryHex = cssColorToHex(primaryColor);
    const backgroundHex = cssColorToHex(backgroundColor);

    const bgHsl = hexToHsl(backgroundHex);

    return {
        BACKGROUND: backgroundHex,
        TONE_1: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 5, 0)),
        TONE_2: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 10, 0)),
        TONE_3: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 15, 0)),
        TONE_4: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 20, 0)),
        TONE_5: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 25, 0)),
        TONE_6: hslToHex(bgHsl.h, bgHsl.s, Math.max(bgHsl.l - 30, 0)),
        ACCENT: primaryHex,
    };
}

export function useSvgBackground(opacity: number = 1) {
    const svgBackgroundUrl = ref('');
    const isClient = ref(false);

    const generateBackground = () => {
        if (!isClient.value) return '';

        // Pegar as cores do CSS
        const style = getComputedStyle(document.documentElement);
        const primaryColor = style.getPropertyValue('--color-primary').trim() || '#f97316';
        const backgroundColor = style.getPropertyValue('--color-background').trim() || '#ffffff';

        const tokenColors = generateTokenColors(primaryColor, backgroundColor);
        const modifiedSvg = replaceSvgTokens(svg, tokenColors);

        if (opacity === 1) {
            return `url("data:image/svg+xml,${encodeURIComponent(modifiedSvg)}")`;
        } else {
            // Pegar a cor de fundo atual e converter para RGB
            const bgHex = cssColorToHex(backgroundColor);
            const r = parseInt(bgHex.slice(1, 3), 16);
            const g = parseInt(bgHex.slice(3, 5), 16);
            const b = parseInt(bgHex.slice(5, 7), 16);

            // Criar um gradiente com opacidade sobre o SVG usando a cor de fundo atual
            return `linear-gradient(rgba(${r}, ${g}, ${b}, ${1 - opacity}), rgba(${r}, ${g}, ${b}, ${1 - opacity})), url("data:image/svg+xml,${encodeURIComponent(modifiedSvg)}")`;
        }
    };

    let observer: MutationObserver | null = null;

    onMounted(() => {
        isClient.value = true;

        // Gerar background inicial
        svgBackgroundUrl.value = generateBackground();

        // Observar mudanças no atributo class do html (onde o dark mode é aplicado)
        observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' &&
                    (mutation.attributeName === 'class' || mutation.attributeName === 'data-theme')) {
                    // Aguardar um tick para as variáveis CSS serem atualizadas
                    setTimeout(() => {
                        svgBackgroundUrl.value = generateBackground();
                    }, 0);
                }
            });
        });

        // Observar mudanças no elemento html e body
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class', 'data-theme']
        });

        if (document.body) {
            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['class', 'data-theme']
            });
        }
    });

    onUnmounted(() => {
        if (observer) {
            observer.disconnect();
        }
    });

    return {
        svgBackgroundUrl,
        generateBackground,
    };
}