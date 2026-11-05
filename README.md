# Laravel Forum

Dit project is mijn praktijkopdracht voor Module 12 - Xperience Fullstack Development.
Ik volg de lessen van meneer Johan Strootman.

## Over de opdracht
Ik maak een forum waar gebruikers berichten kunnen plaatsen en op berichten kunnen reageren.
Mijn forum is voor studenten van de opleiding Software Development. Zij kunnen hier technische vragen stellen en elkaar helpen.

## Wat kan het forum?
| Wie | Wat kan deze persoon? |
| --- | --- |
| Bezoeker | Threads, topics en replies lezen, registreren en inloggen |
| User | Topics en replies maken, eigen topics en replies aanpassen |
| Admin | Threads toevoegen, alle berichten aanpassen en verwijderen, users bekijken, aanpassen en verwijderen |

Gewone users kunnen geen berichten verwijderen. Een wachtwoord wordt nooit getoond.

## Technieken
| Techniek | Waarvoor gebruik ik het? |
| --- | --- |
| Laravel 13 (PHP) | Het framework van de applicatie |
| MVC | Models, views en controllers hebben elk een eigen taak |
| Laravel Breeze | Registreren, inloggen en uitloggen |
| Blade en Tailwind CSS | De pagina's en de vormgeving |
| SQLite | De database |
| Migrations | De tabellen beschrijven in code |
| Eloquent-relaties | `hasMany` en `belongsTo` tussen users, threads, topics en replies |
| Seeder | Voorbeeldgegevens in de database zetten |
| Validatie | Invoer controleren op de server |
| Middleware | `auth` voor ingelogde gebruikers, `admin` voor administrators |
| `@csrf` en `Fillable` | Formulieren beveiligen en bepalen welke velden opgeslagen mogen worden |
| Clean URL's | Bijvoorbeeld `/thread/1/topic/3` |
| PHPUnit | Automatische tests |
| Git en GitHub | Versiebeheer |

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

`migrate:fresh` verwijdert alle bestaande gegevens en maakt de tabellen opnieuw.
De seeder maakt een admin (`admin@example.com`) en een user (`test@example.com`). De testwachtwoorden staan in `database/seeders/DatabaseSeeder.php`.

Tests uitvoeren:

```bash
php artisan test
```

## Welke stappen heb ik gedaan?

### 1. PHP voorbereiden
Composer kon Laravel eerst niet installeren, omdat de PHP-extensie `fileinfo` uit stond.
Daarom heb ik `extension=php_fileinfo.dll` toegevoegd aan `C:\MAMP\conf\php8.3.1\php.ini`.

| Commando | Waarom? |
| --- | --- |
| `notepad C:\MAMP\conf\php8.3.1\php.ini` | Het configuratiebestand van PHP openen |
| `php -r "echo extension_loaded('fileinfo') ? 'yes' : 'no';"` | Controleren of `fileinfo` aan staat |

### 2. Laravel en Breeze installeren
| Commando | Waarom? |
| --- | --- |
| `cd C:\Users\kingc\Herd` | Naar de map gaan waarin ik het project wil maken |
| `composer create-project laravel/laravel forum` | Een nieuw Laravel-project maken |
| `cd forum` | Naar de projectmap gaan |
| `php artisan --version` | Controleren of Laravel werkt |
| `composer require laravel/breeze --dev` | Breeze toevoegen |
| `php artisan breeze:install blade --no-interaction` | Inloggen en registreren laten maken, met Blade |
| `php artisan route:list --path=login` | Controleren of de login-routes bestaan |
| `php artisan serve` | De website lokaal starten |

In `config/app.php` heb ik de tijdzone ingesteld op `Europe/Amsterdam`.

### 3. Git en GitHub
| Commando | Waarom? |
| --- | --- |
| `git init` | Een Git-repository maken |
| `git add .` | De bestanden klaarzetten voor een commit |
| `git commit -m "Laravel-Forum"` | De eerste versie opslaan |
| `git remote add origin https://github.com/Sachhh-star/laravel-forum.git` | Het project koppelen aan GitHub |
| `git branch -M main` | De hoofdbranch `main` noemen |
| `git push -u origin main` | De code naar GitHub sturen |

`.env`, `vendor` en `node_modules` staan niet op GitHub. `.env` bevat instellingen die geheim moeten blijven.

### 4. Username en rol toevoegen
De opdracht vraagt om een gebruikersnaam en twee soorten gebruikers.

- De migration van `users` kreeg de kolommen `username` en `role`.
- `unique()` zorgt dat een username niet dubbel in de database kan staan.
- De standaardrol is `user`.
- `role` staat bewust niet in `Fillable`, zodat iemand zichzelf niet via een formulier admin kan maken.
- Het registratieformulier en de controller kregen een veld en validatie voor `username`.

| Commando | Waarom? |
| --- | --- |
| `php artisan migrate:fresh` | Alle tabellen opnieuw maken met de nieuwe kolommen |

### 5. Database en relaties
- Ik heb migrations gemaakt voor `threads`, `topics` en `replies`.
- De foreign keys `user_id`, `thread_id` en `topic_id` leggen de verbanden tussen de tabellen.
- Met `onDelete('cascade')` worden de berichten van een verwijderde user ook verwijderd.
- In de models staan de relaties met `hasMany` en `belongsTo`.
- Een seeder zet testgegevens in de database.

### 6. Pagina's om te lezen en te reageren
- De startpagina toont de threads, met de tekst afgekapt op een regel.
- De threadpagina toont de hele tekst en de topics.
- De topicpagina toont de tekst, de naam van de maker en de replies.
- Ingelogde gebruikers zien een formulier om een reply te plaatsen. `validate` controleert de invoer en `@csrf` beveiligt het formulier.
- `scopeBindings` controleert of een topic echt bij de thread in de URL hoort.

### 7. Rechten, beheer en afronding
- Gebruikers kunnen topics maken en eigen topics en replies aanpassen.
- `canEdit` in `app/Models/User.php` bepaalt wie een bericht mag aanpassen.
- De middleware `EnsureUserIsAdmin` beschermt de routes voor administrators.
- Een admin kan threads toevoegen en berichten verwijderen.
- `UserController` en de pagina's Users en User Detail zijn voor het beheer van users.
- Gebruikers kunnen hun eigen account niet verwijderen. Dat kan alleen een admin. Een admin kan zichzelf niet verwijderen of de eigen rol aanpassen.
- De navigatie, de vormgeving en de voorbeeldgegevens zijn toegevoegd.
- `tests/Feature/ForumTest.php` test de belangrijkste eisen.

| Commando | Waarom? |
| --- | --- |
| `php artisan test` | Alle automatische tests uitvoeren |

## Problemen die ik heb opgelost
- **`fileinfo` stond uit:** Composer kon Laravel niet installeren. Ik heb de extensie aangezet in `php.ini`.
- **README op GitHub:** de tekst werd verkeerd getoond door twee soorten encoding. Ik heb het bestand opgeslagen als UTF-8.
- **`git push` gaf Internal Server Error:** dit was een fout bij GitHub. Na een nieuwe poging werkte het.
