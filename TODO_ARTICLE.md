# Article Feature Implementation Plan

## Task: Add Article CRUD to Admin Section with categories (gizi_anak, ibu_hamil, imunisasi)

### Phase 1: Database & Model
- [ ] 1. Create migration: `2026_05_13_000000_create_articles_table.php`
- [ ] 2. Create Article Model: `app/Models/Article.php`

### Phase 2: Admin Controller & Routes
- [ ] 3. Add article CRUD methods to `AdminController.php`
- [ ] 4. Add routes in `routes/web.php` for article management
- [ ] 5. Create admin view: `resources/views/admin/artikel.blade.php`

### Phase 3: User View Updates
- [ ] 6. Update `DashboardController.php` to fetch articles from database
- [ ] 7. Verify `dashboard/artikel.blade.php` works with database data

### Phase 4: Testing
- [ ] 8. Run migration
- [ ] 9. Test admin CRUD
- [ ] 10. Test user view displays articles

## Notes:
- Categories: "gizi_anak" (Gizi Anak), "ibu_hamil" (Ibu Hamil), "imunisasi" (Imunisasi)
- Preserve existing static articles as seed data
- Don't modify existing user data/images in public/images/
