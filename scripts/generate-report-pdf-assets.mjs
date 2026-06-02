/**
 * Build public/assets report borders (from reference screenshots) and standardize logo paths.
 * Run: node scripts/generate-report-pdf-assets.mjs
 */
import sharp from 'sharp';
import { copyFile, mkdir, access } from 'fs/promises';
import { constants } from 'fs';
import { dirname, resolve } from 'path';
import { fileURLToPath } from 'url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const assetsDir = resolve(root, 'public/assets');
const cursorAssets = resolve(
    process.env.HOME || '',
    '.cursor/projects/Users-lylesmac-Laravel-Projects-feature-single-db-unified/assets',
);

const landscapeRef = resolve(
    cursorAssets,
    'Screenshot_2026-06-01_at_8.34.18_AM-382e307a-74b6-4701-a87b-27252094d346.png',
);
const portraitRef = resolve(
    cursorAssets,
    'Screenshot_2026-06-01_at_8.34.33_AM-1cc76383-e757-46b9-b340-03ffbab831e2.png',
);

const BORDER_WIDTH = 46;

async function exists(path) {
    try {
        await access(path, constants.F_OK);
        return true;
    } catch {
        return false;
    }
}

async function extractBorder(sourcePath, side, outPath) {
    if (!(await exists(sourcePath))) {
        console.warn('Skip border (missing reference):', sourcePath);
        return false;
    }

    const meta = await sharp(sourcePath).metadata();
    const width = meta.width ?? 0;
    const height = meta.height ?? 0;
    if (width < BORDER_WIDTH * 2 || height < 100) {
        console.warn('Skip border (image too small):', sourcePath);
        return false;
    }

    const extract =
        side === 'left'
            ? { left: 0, top: 0, width: BORDER_WIDTH, height }
            : { left: width - BORDER_WIDTH, top: 0, width: BORDER_WIDTH, height };

    let pipeline = sharp(sourcePath).extract(extract);
    if (side === 'right') {
        pipeline = pipeline.flop();
    }

    await pipeline.png({ compressionLevel: 9 }).toFile(outPath);
    console.log('OK', outPath);
    return true;
}

async function copyIfExists(from, to) {
    if (!(await exists(from))) {
        return false;
    }
    await copyFile(from, to);
    console.log('OK', to);
    return true;
}

await mkdir(assetsDir, { recursive: true });

await extractBorder(landscapeRef, 'left', resolve(assetsDir, 'report-border-left.png'));
await extractBorder(landscapeRef, 'right', resolve(assetsDir, 'report-border-right.png'));

if (await exists(portraitRef)) {
    await extractBorder(portraitRef, 'left', resolve(assetsDir, 'report-border-left-portrait.png'));
    await extractBorder(portraitRef, 'right', resolve(assetsDir, 'report-border-right-portrait.png'));
}

const logoCopies = [
    ['public/SYSTEMLOGO.png', 'Lgu Socmed Template-02.png'],
    ['public/Love Impasugong.png', 'Love Impasugong-04.png'],
    ['public/LBP.png', 'Bagong_Pilipinas_logo.png'],
];

for (const [srcRel, destName] of logoCopies) {
    const from = resolve(root, srcRel);
    const to = resolve(assetsDir, destName);
    if (!(await exists(to))) {
        await copyIfExists(from, to);
    }
}

console.log('Done.');
