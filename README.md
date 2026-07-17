# LuxurEat Static WordPress Theme

This package wraps the static bilingual LuxurEat website source from https://github.com/errpenk/luxureat-website-source as a WordPress theme.

## Install

1. Upload `luxureat-static-theme.zip` in WordPress: Appearance -> Themes -> Add New -> Upload Theme.
2. Activate **LuxurEat Static**.
3. Open Settings -> Permalinks once and save if routes do not appear immediately.

## Routes

- `/` serves the Chinese home page.
- Default Chinese routes use root-level pretty URLs such as `/caviar/`, `/rituals/`, and `/contact/`.
- English routes use `/en/`, `/en/products/`, and the rest of the `/en/.../` namespace.

## Notes

- The current version prioritizes visual fidelity and static routing.
- Local assets and domain scripts are loaded through WordPress theme APIs.
- Products, events, journal, and brand content each have dedicated data, script, and media locations under `assets/`.
