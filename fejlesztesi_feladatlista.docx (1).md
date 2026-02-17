**BESZERZÉS-LOGISZTIKA – FEJLESZTÉSI FELADATLISTA**

| ☐ | Azonosító | Feladat | Részletes leírás |
| :---: | ----- | ----- | ----- |
| ☐ | **F1-01** | **Rendelés tételsorok (Orders)** | Orders modulban tételsor funkció: termék kiválasztás (dropdown), mennyiség, egységár, összeg (auto számítás), adószázalék mező. Tételek hozzáadása/törlése dinamikusan. Összesítő sor az űrlap alján. |
| ☐ | **F1-02** | **Bevételezés tételsorok (Receipts)** | Receipts modulban tételsor funkció: rendelésből előtöltés, érkezett mennyiség, minőségi státusz soronként. Részleges bevételezés támogatás (pl. 100-ból 80 db érkezett). |
| ☐ | **F1-03** | **Supplier Price List modul** | Új modul: termékenként több beszállítói ár rögzítése. Mezők: Beszállító, Termék, Egységár, Pénznem, Érvényesség kezdete/vége, Min. rendelési mennyiség. Mennyiségi kedvezmény sávok. |
| ☐ | **F1-04** | **Árlista CSV/Excel import** | Supplier Price List modulhoz CSV és Excel fájl importálás. Oszlop mapping felület, előnézet, hibajelzés. Példa CSV letöltés gomb. |
| ☐ | **F1-05** | **Bizonylat történet nézet** | Rendelés részletes nézetén 'Bizonylat történet' tab/szekció: timeline nézet, amely mutatja a rendeléshez kapcsolódó összes bizonylat státuszát (Rendelés → Szállítás → Bevételezés → Számla). |
| ☐ | **F1-06** | **Orders → Receipts automatikus előtöltés** | Bevételezés létrehozásakor rendelés kiválasztása esetén automatikusan töltődjenek be a tételsorok (termék, rendelt mennyiség). A felhasználó csak az érkezett mennyiséget írja be. |
| ☐ | **F1-07** | **Leltárív PDF generálás** | Inventories modulból 'Leltárív nyomtatás' gomb: PDF generálás a kiválasztott raktár termékeivel. Oszlopok: cikkszám, megnevezés, könyv szerinti mennyiség (opcionálisan üres), tényleges mennyiség (kézzel kitöltendő), eltérés. |
| ☐ | **F1-08** | **Leltár korrekció funkció** | Leltár lezárásakor az eltérések automatikus készletkorrekciót generáljanak. Hiány/többlet rögzítés soronként, indoklás mező, jóváhagyási lépés. |
| ☐ | **F1-09** | **Teljes magyar lokalizáció** | Laravel nyelvi fájlok (lang/hu) teljes frissítése. Részletek a fordítási táblázatban (lásd melléklet). |

| ☐ | Azonosító | Feladat | Részletes leírás |
| :---: | ----- | ----- | ----- |
| ☐ | **F2-01** | **Menüstruktúra átszervezés** | Menü átrendezés az alábbi csoportosítás szerint: Vezérlőpult → Törzsadatok (Termékek, Kategóriák, Beszállítók, Raktárak) → Készletkezelés (Készletek, Tételek/Batches, Visszáru, Leltározás) → Beszerzés (Rendelések, Bevételezések, Várható érkezések, Partnerek) → Riportok → Intrastat → Adminisztráció. |
| ☐ | **F2-02** | **'Értékesítés' átnevezés → 'Beszerzés'** | Az 'Értékesítés' menücsoport átnevezése 'Beszerzés'-re. Sidebar, breadcrumb, oldalcímek frissítése. |
| ☐ | **F2-03** | **Konszignációs raktár típus** | Warehouses modulban új mező: 'Raktár típus' (Saját / Konszignációs). Konszignációs raktár készlete külön jelöljön megjelenjen a riportokban, ne számítson bele a saját készletértékbe. |
| ☐ | **F2-04** | **Foglaltság (reserved stock) kezelés** | Készlet nézetben új oszlop: 'Foglalt mennyiség' és 'Szabad készlet' (= Jelenlegi \- Foglalt). Értékesítési rendeléshez lefoglalt készlet automatikusan csökkentse a szabad készletet. |
| ☐ | **F2-05** | **Vonalkód generálás és kezelés** | Products modulban vonalkód/QR kód automatikus generálás vagy kézi megadás. Barcode mező már létezik – bővítés: generálás gomb, nyomtatás címkére (PDF). Bevételezésnél kamera/scanner támogatás. |
| ☐ | **F2-06** | **Billingo API integráció** | Beszerzési számla rögzítése Billingo-n keresztül. API token beállítás az Admin/Beállítások-ban. Bevételezés lezárásakor opcionális számla generálás Billingo felé. |
| ☐ | **F2-07** | **AI Asszisztens gomb a nav-ban** | Az AI Asszisztens chat gomb elhelyezése a felső navigációs sávban (keresés mező mellett), hogy minden oldalról elérhető legyen. Floating chat ablak. |

| ☐ | Azonosító | Feladat | Részletes leírás |
| :---: | ----- | ----- | ----- |
| ☐ | **F3-01** | **Raktárhely kezelés** | Warehouses modulon belül raktárhely hierarchia: Raktár → Zóna → Sor → Polc → Szint. Termék hozzárendelés raktárhelyhez. Betárolás/kitárolás raktárhely megadásával. |
| ☐ | **F3-02** | **Automatikus rendelési javaslat** | Dashboard widget vagy külön menüpont: 'Rendelési javaslatok'. Min. készlet alatti termékek listázása, javasolt rendelési mennyiség (= Max. készlet \- Jelenlegi készlet), egy kattintással rendelés létrehozás. |
| ☐ | **F3-03** | **Készletforgási riport (ABC elemzés)** | Riportok menüben új riport: ABC elemzés. Termékek kategorizálása forgalmi érték alapján (A: top 20%, B: közép 30%, C: alsó 50%). Forgási sebesség, átlagos készletszint számítás. |
| ☐ | **F3-04** | **Beszállítói teljesítmény riport** | Riportok menüben új riport: beszállítónkénti statisztikák – szállítási határidő tartás %, átlagos késés (nap), minőségi visszautasítás %, rendelési érték. |
| ☐ | **F3-05** | **Dashboard KPI widgetek bővítés** | Dashboard-on további KPI kártyák: Havi beszerzési költség, Top 5 beszállító, Lejáró sarzsok figyelmeztetés, Rendelési javaslatok szám. |

| ☐ | Jelenlegi (angol/vegyes) | Cél (magyar) |
| :---: | ----- | ----- |
| ☐ | Új Batch | Új Sarzs |
| ☐ | Új Category | Új Kategória |
| ☐ | Új Inventory | Új Leltár |
| ☐ | Új Product | Új Termék |
| ☐ | Új Customer | Új Partner |
| ☐ | Új Receipt | Új Bevételezés |
| ☐ | Expected Stock Arrivals | Várható Készletérkezések |
| ☐ | Inventory Valuation Report | Készletértékelési Jelentés |
| ☐ | Basic Information | Alapadatok |
| ☐ | Dates | Dátumok |
| ☐ | Quantity & Status | Mennyiség és Státusz |
| ☐ | Serial Numbers / Gyártási Számok | Gyártási Számok |
| ☐ | Classification | Besorolás |
| ☐ | Measurements | Méretek |
| ☐ | Pricing | Árazás |
| ☐ | Stock Management | Készletkezelés |
| ☐ | Customer Information | Partner adatok |
| ☐ | Billing Address | Számlázási cím |
| ☐ | Shipping Address | Szállítási cím |
| ☐ | Financial Information | Pénzügyi adatok |
| ☐ | Pending Check | Ellenőrzésre vár |
| ☐ | In Progress | Folyamatban |
| ☐ | Delivered | Kiszállítva |
| ☐ | Létrehozás (gomb) | Létrehozás |
| ☐ | Mentés és új létrehozása | Mentés és új létrehozása |
| ☐ | Mégsem | Mégsem |
| ☐ | Credit limit | Hitelkeret / Fizetési feltétel |
| ☐ | Balance | Egyenleg |
| ☐ | Conducted by | Végrehajtó |
| ☐ | Received by | Átvevő |

