# FITstagram

## Autoři
- **Radim Mifka**  
  [xmifka00@stud.fit.vutbr.cz](mailto:xmifka00@stud.fit.vutbr.cz) - BE, FE, CI/CD  
- **Miroslav Bálek**  
  [xbalek02@stud.fit.vutbr.cz](mailto:xbalek02@stud.fit.vutbr.cz) - BE, FE, DB  

## URL aplikace
[https://nitte.cz/](https://nitte.cz/)

## Uživatelé systému pro testování
Uveďte existující zástupce **všech rolí uživatelů**.

| Login       | Heslo           | Role          |
|-------------|-----------------|---------------|
| admin       | ib3wVqnRY4bQRLm | Administrátor |
| moderator0  | s6sATaFffBPZICZ | Moderátor     |
| stepanka08  | xPassword1242   | Uživatel      |

## Implementace
Jednotlivé případy použití v aplikaci jsou realizovány pomocí kontrolerů. Přehled hlavních kontrolerů a jejich účelu:
- **AuthController.php**  
  Správa autentizace: přihlášení, odhlášení, registrace uživatele.
- **CommentController.php**  
  Operace s komentáři: vytváření, mazání komentářů.
- **GroupController.php**  
  Správa skupin: vytváření, úprava, mazání a zobrazení informací o skupinách.
- **GroupMembershipController.php**  
  Správa členství ve skupinách: správa žádostí a odstraňování členů.
- **LanguageController.php**  
  Nastavení jazykových preferencí: změna jazyka a správa lokalizace.
- **PostController.php**  
  Operace s příspěvky: vytváření, mazání, hlasování a zobrazování příspěvků.
- **SiteController.php**  
  Obecné stránky: domovská stránka, informace, kontaktní formulář.
- **TagController.php**  
  Správa štítků: přidávání, úprava, mazání a zobrazování štítků.
- **UserController.php**  
  Správa uživatelů: informace o profilu, úprava údajů, seznam uživatelů, blokování a odstranění.

## Bezpečnostní prvky
- **Role-Based Access Control (RBAC)**  
  Systém řízení přístupu založený na rolích implementovaný pomocí Yii2 RBAC manageru. Oprávnění jsou definována v souboru `migrations/m241121_180103_init_rbac.php`.
- **HTTPS Zabezpečení**
- **Google reCAPTCHA v3**  
  Ochrana proti automatizovaným útokům a spamu. Konfigurováno v souboru `.env`:
  - `RECAPTCHA_SITE_KEY` - Veřejný klíč pro frontend
  - `RECAPTCHA_SECRET_KEY` - Privátní klíč pro backend

## Databáze
![Databázové schéma](./docs/dbschema.png)

## Přehled systému
Aplikace využívá framework Yii2 a moderní technologie:
- Kontejnerizace pomocí Dockeru zajišťuje konzistentní prostředí.
- Frontend je plně responzivní a podporuje vícejazyčné rozhraní (čeština, angličtina).

## Instalace

### Instalace na server
Projekt využívá CI/CD pipeline pomocí GitHub Actions. Po pushnutí do větve `main`:
1. Spustí se automatická kontrola kódu pomocí PHPStan (level 8).
2. V případě úspěšné kontroly se provede deployment na FTP server.
3. Automaticky se aplikují databázové migrace.

Pipeline konfigurace: `.github/workflows/main.yml`

Nastavení aplikace se provádí pomocí souboru `.env`, který se vytvoří při nahrávání na server.

### Softwarové požadavky
Všechny požadavky jsou zapouzdřeny v Docker containeru:
- PHP 8.3.*
- Node 20.*
- Composer 2.7.2
- Docker a Docker Compose (pro lokální vývoj)

#### Framework a knihovny
- Yii2 Framework
- Tailwind CSS pro stylování
- PHPStan pro statickou analýzu kódu

## Inicializace databáze
1. Spusťte příkaz `php yii migrate/fresh` pro vytvoření nové struktury databáze.
2. Spusťte příkaz `php yii seed` pro naplnění databáze testovacími daty.

## Známé nedostatky
- Nelze odebrat dříve přidané tagy při úpravě příspěvku.
- Web není plně funkční bez povoleného JavaScriptu.

