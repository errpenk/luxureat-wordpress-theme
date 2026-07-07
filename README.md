# LuxurEat Static WordPress Theme

This package wraps the static bilingual LuxurEat website source from https://github.com/errpenk/luxureat-website-source as a WordPress theme.

## Install

1. Upload `luxureat-static-theme.zip` in WordPress: Appearance -> Themes -> Add New -> Upload Theme.
2. Activate **LuxurEat Static**.
3. Open Settings -> Permalinks once and save if routes do not appear immediately.

## Routes

- `/` serves the Chinese home page.
- Other routes use query URLs such as `/?luxureat_path=zh/caviar` and `/?luxureat_path=en`, so the site works even when the host does not forward pretty permalinks to WordPress.
- Pretty routes such as `/zh/` and `/en/caviar/` are still registered and can work after the host rewrite layer is configured.

## Notes

- The current version prioritizes visual fidelity and static routing.
- Local assets, `integration.css`, and `main.js` are loaded through WordPress theme APIs.
- Most photographic images remain the original external prototype image URLs.
