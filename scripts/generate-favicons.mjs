/**
 * Raster favicons resized to tight squares (cover) — reads bolder in 16×16 / 32×32 tabs.
 *
 * npm run favicons:generate
 */
import { mkdirSync } from 'node:fs';
import path from 'node:path';
import sharp from 'sharp';

const publicDir = path.join(process.cwd(), 'public');
const outDir = path.join(publicDir, 'favicons');
mkdirSync(outDir, { recursive: true });

const sources = [
    { src: 'Love Impasugong 2.png', stem: 'love' },
    { src: 'Lgu Socmed Template-02 2.png', stem: 'lgu' },
];
const sizes = [16, 32, 48, 64, 96, 128, 180, 192, 256, 512];

for (const { src, stem } of sources) {
    const input = path.join(publicDir, src);
    for (const s of sizes) {
        await sharp(input)
            .resize(s, s, { fit: 'cover', position: 'centre' })
            .png()
            .toFile(path.join(outDir, `${stem}-${s}.png`));
        console.log(` wrote favicons/${stem}-${s}.png`);
    }
}
