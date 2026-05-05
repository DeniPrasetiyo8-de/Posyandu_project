# Todo: Move Laporan Kegiatan from Landing Page to User Dashboard

## Task Summary
Move activity reports (laporan kegiatan) with images from home.blade.php to dashboard/index.blade.php, then remove them from home.blade.php.

## Steps:

### Step 1: Add Laporan Kegiatan section to dashboard/index.blade.php
- Location: Bottom of the dashboard content
- Content to add: 
  - Section title with icon
  - 3 cards with images:
    - G Cek Kehamilan.jpg (Cek Kehammadiamilan)
    - G Pemberian Vitamin.jpg (Pemberian Vitamin)
    - G Imunisasi Anak.jpg (Imunisasi Anak)

### Step 2: Remove Laporan Kegiatan section from home.blade.php
- Remove the entire "Laporan Kegiatan Posyandu" section with images

## Files to edit:
1. `resources/views/dashboard/index.blade.php` - Add laporan kegiatan section at bottom
2. `resources/views/home.blade.php` - Remove laporan kegiatan section
