# Laravel Forum

Dit project is mijn praktijkopdracht voor Module 12 - Xperience Fullstack Development.
Ik volg de lessen van meneer Johan Strootman.

## Over de opdracht
Ik maak een forum waar gebruikers berichten kunnen plaatsen en op berichten kunnen reageren.
Mijn forum is voor studenten van de opleiding Software Development. Zij kunnen hier technische vragen stellen en elkaar helpen.

## Technieken
- Laravel 13
- Laravel Breeze
- Blade
- SQLite

## Status
- [x] Laravel en Breeze geïnstalleerd
- [x] Registreren en inloggen
- [x] Database voor threads, topics en replies
- [ ] Startpagina en topicpagina
- [ ] Rollen voor administrator en user
- [ ] Users-pagina en User Detail-pagina

## Project starten
Voer deze commando's uit in de projectmap:

```bash
composer install
npm install
npm run build
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Open daarna http://127.0.0.1:8000 in de browser.