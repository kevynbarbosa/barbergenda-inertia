import { defineConfig, type Preset } from '@vite-pwa/assets-generator/config';

// Preset customizado baseado na configuração do laravelpwa.php
const presetLaravelPWA: Preset = {
    // Ícones transparentes (para PWA) - baseado nos tamanhos do laravelpwa.php
    transparent: {
        sizes: [72, 96, 128, 144, 152, 192, 384, 512], // Tamanhos definidos no laravelpwa.php
        favicons: [[48, 'favicon.ico']], // Favicon padrão
        padding: 0.05, // Padding mínimo para ícones transparentes
    },

    // Ícones mascaráveis (para Android) - apenas os tamanhos recomendados
    maskable: {
        sizes: [192, 512], // Tamanhos recomendados para maskable
        padding: 0.3, // Padding maior para ícones mascaráveis
    },

    // Ícones para Apple/iOS
    apple: {
        sizes: [180], // Tamanho padrão para apple-touch-icon
        padding: 0.2, // Padding moderado para ícones Apple
    },

    // Apple Splash Screens (Startup Images)
    appleStartupImages: {
        sizes: [
            [640, 1136],   // iPhone 5/5s/5c/SE
            [750, 1334],   // iPhone 6/6s/7/8
            [828, 1792],   // iPhone XR
            [1125, 2436],  // iPhone X/XS
            [1242, 2208],  // iPhone 6+/6s+/7+/8+
            [1242, 2688],  // iPhone XS Max
            [1536, 2048],  // iPad (9.7")
            [1668, 2224],  // iPad Pro (10.5")
            [1668, 2388],  // iPad Pro (11")
            [2048, 2732],  // iPad Pro (12.9")
        ],
        padding: 0.3, // Padding para splash screens
        resizeOptions: {
            fit: 'contain',
            background: '#ffffff', // Fundo branco para splash screens
        },
    },

    // Função customizada para nomear os arquivos PNG conforme padrão do laravelpwa.php
    assetName: (type, size) => {
        switch (type) {
            case 'transparent':
                return `icon-${size.width}x${size.height}.png`;
            case 'maskable':
                return `icon-${size.width}x${size.height}-maskable.png`;
            case 'apple':
                return `apple-touch-icon-${size.width}x${size.height}.png`;
            case 'appleStartupImages':
                return `splash-${size.width}x${size.height}.png`;
            default:
                return `icon-${size.width}x${size.height}.png`;
        }
    },

    // Configurações de otimização PNG
    png: {
        compressionLevel: 9, // Máxima compressão
        quality: 85, // Qualidade alta (0-100)
    },
};

export default defineConfig({
    headLinkOptions: {
        preset: '2023', // Usar preset 2023 para HTML
        basePath: '/pwa/', // Caminho base conforme laravelpwa.php
    },
    preset: presetLaravelPWA,
    images: ['public/pwa/logo.png'], // Usar o logo disponível no diretório public
});
