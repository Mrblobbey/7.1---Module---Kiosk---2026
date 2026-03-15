-- ============================================================
-- Happy Herbivore Kiosk — kiosk.sql
-- Importeer via: mysql -u root -p < kiosk.sql
-- Of via phpMyAdmin: Importeer > Kies bestand
-- ============================================================

CREATE DATABASE IF NOT EXISTS `happy_herbivore_kiosk`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `happy_herbivore_kiosk`;

-- Verwijder bestaande tabellen (juiste volgorde vanwege foreign keys)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `bestelling_regels`;
DROP TABLE IF EXISTS `bestellingen`;
DROP TABLE IF EXISTS `producten`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS `producten` (
    `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `naam_nl`         VARCHAR(150)    NOT NULL,
    `naam_en`         VARCHAR(150)    NOT NULL,
    `naam_de`         VARCHAR(150)    NOT NULL,
    `beschrijving_nl` TEXT            NULL,
    `beschrijving_en` TEXT            NULL,
    `beschrijving_de` TEXT            NULL,
    `prijs`           DECIMAL(6,2)    NOT NULL DEFAULT 0.00,
    `categorie`       ENUM('ontbijt','bowls','handhelds','sides','dips','dranken','overig') NOT NULL DEFAULT 'overig',
    `kcal`            SMALLINT        NOT NULL DEFAULT 0,
    `is_vegan`        TINYINT(1)      NOT NULL DEFAULT 0,
    `is_vegetarisch`  TINYINT(1)      NOT NULL DEFAULT 1,
    `actief`          TINYINT(1)      NOT NULL DEFAULT 1,
    `volgorde`        TINYINT         NOT NULL DEFAULT 0,
    `aangemaakt_op`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bestellingen` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `besteltype`    ENUM('hier_eten','meenemen') NOT NULL DEFAULT 'hier_eten',
    `taal`          CHAR(2)       NOT NULL DEFAULT 'nl',
    `totaal_prijs`  DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    `betaalmethode` ENUM('pin','contant') NOT NULL DEFAULT 'pin',
    `ophaalcode`    SMALLINT      NOT NULL DEFAULT 0,
    `status`        ENUM('nieuw','in_behandeling','klaar','geannuleerd') NOT NULL DEFAULT 'nieuw',
    `aangemaakt_op` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bestelling_regels` (
    `id`             INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `bestelling_id`  INT UNSIGNED     NOT NULL,
    `product_id`     INT UNSIGNED     NOT NULL,
    `product_naam`   VARCHAR(150)     NOT NULL,
    `aantal`         TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `prijs_per_stuk` DECIMAL(6,2)     NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`bestelling_id`) REFERENCES `bestellingen`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`)    REFERENCES `producten`(`id`)    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `producten` (`naam_nl`,`naam_en`,`naam_de`,`beschrijving_nl`,`beschrijving_en`,`beschrijving_de`,`prijs`,`categorie`,`kcal`,`is_vegan`,`is_vegetarisch`,`volgorde`) VALUES
('Morning Boost Açaí Bowl','Morning Boost Açaí Bowl','Morning Boost Açaí Bowl','Gekoeld açaí met banaan, knapperige granola, chiazaad en kokos.','Chilled açaí and banana topped with crunchy granola, chia seeds, and coconut.','Gekühltes Açaí mit Banane, knusprigem Granola, Chiasamen und Kokos.',7.50,'ontbijt',320,1,1,1),
('The Garden Breakfast Wrap','The Garden Breakfast Wrap','The Garden Breakfast Wrap','Volkorenwrap met roerei, babyspinazie en yoghurt-kruidensaus.','Whole-grain wrap with fluffy scrambled eggs, baby spinach, and yogurt-herb sauce.','Vollkornwrap mit Rührei, Babyspinat und Joghurt-Kräutersauce.',6.50,'ontbijt',280,0,1,2),
('Peanut Butter & Cacao Toast','Peanut Butter & Cacao Toast','Erdnussbutter & Kakao Toast','Zuurdesemtoast met 100% pindakaas, banaan en cacaonibs.','Sourdough toast with 100% natural peanut butter, banana, and cacao nibs.','Sauerteigtoast mit 100% Erdnussbutter, Banane und Kakaonibs.',5.00,'ontbijt',240,1,1,3),
('Overnight Oats: Appeltaart Stijl','Overnight Oats: Apple Pie Style','Overnight Oats: Apfelkuchen-Stil','Havermout in amandelmelk met geraspte appel, kaneel en walnoten.','Oats soaked in almond milk with grated apple, cinnamon, and crushed walnuts.','Haferflocken in Mandelmilch mit geriebenem Apfel, Zimt und Walnüssen.',5.50,'ontbijt',290,1,1,4),
('Tofu Power Tahini Bowl','Tofu Power Tahini Bowl','Tofu Power Tahini Bowl','Driekleurige quinoa, esdoorntofu, zoete aardappel en boerenkool met tahinidressing.','Tri-color quinoa, maple-glazed tofu, roasted sweet potatoes, and kale with tahini dressing.','Dreifarbige Quinoa, Ahorn-Tofu, geröstete Süßkartoffeln und Grünkohl mit Tahini-Dressing.',10.50,'bowls',480,1,1,1),
('The Supergreen Harvest','The Supergreen Harvest','The Supergreen Harvest','Gemasseerde boerenkool, edamame, avocado, komkommer en pompoenpitten.','Massaged kale, edamame, avocado, cucumber, and toasted pumpkin seeds with lemon-olive oil.','Massierter Grünkohl, Edamame, Avocado, Gurke und Kürbiskerne mit Zitronen-Olivenöl.',9.50,'bowls',310,1,1,2),
('Mediterranean Falafel Bowl','Mediterranean Falafel Bowl','Mediterrane Falafel Bowl','Gebakken falafel, hummus, ingelegde rode ui, cherrytomaatjes en komkommer.','Baked falafel, hummus, pickled red onions, cherry tomatoes, and cucumber on greens.','Gebackener Falafel, Hummus, eingelegte rote Zwiebeln, Kirschtomaten und Gurke.',10.00,'bowls',440,1,1,3),
('Warm Teriyaki Tempeh Bowl','Warm Teriyaki Tempeh Bowl','Warme Teriyaki Tempeh Bowl','Gestoomde zilvervliesrijst, tempeh, broccoli en wortel met gember-sojasaus.','Steamed brown rice, seared tempeh, broccoli, and carrots with a ginger-soy glaze.','Gedämpfter Vollkornreis, Tempeh, Brokkoli und Karotten mit Ingwer-Sojaglasur.',11.00,'bowls',500,1,1,4),
('Zesty Chickpea Hummus Wrap','Zesty Chickpea Hummus Wrap','Würziger Kichererbsen-Hummus-Wrap','Gekruide kikkererwten, geraspte wortel, sla en hummus in een volkorenwrap.','Spiced chickpeas, shredded carrots, crisp lettuce, and signature hummus in a whole-wheat wrap.','Gewürzte Kichererbsen, geraspelte Karotten, knackiger Salat und Hummus in einem Vollkornwrap.',8.50,'handhelds',410,1,1,1),
('Avocado & Halloumi Toastie','Avocado & Halloumi Toastie','Avocado & Halloumi Toastie','Gegrilde halloumi, gepureerde avocado en chilivlokken op meerzadenbrood.','Grilled halloumi cheese, smashed avocado, and chili flakes on thick-cut multi-grain bread.','Gegrillter Halloumi, zerdrückte Avocado und Chiliflocken auf Mehrkornbrot.',9.00,'handhelds',460,0,1,2),
('Smoky BBQ Jackfruit Slider','Smoky BBQ Jackfruit Slider','Smoky BBQ Jackfruit Slider','Pulled jackfruit in BBQ-saus met paarse coleslaw op een vegan briochebroodje.','Pulled jackfruit in BBQ sauce with a crunchy purple slaw on a vegan brioche bun.','Pulled Jackfruit in BBQ-Sauce mit knusprigem lila Coleslaw auf einem veganen Briochebrötchen.',7.50,'handhelds',350,1,1,3),
('Zoete Aardappel Wedges','Oven-Baked Sweet Potato Wedges','Ofengebackene Süßkartoffelwedges','Gekruid met gerookt paprikapoeder. Lekker met Avocado Limoen Crema.','Seasoned with smoked paprika. Best with Avocado Lime Dip.','Mit geräuchertem Paprika gewürzt. Am besten mit Avocado-Limetten-Dip.',4.50,'sides',260,1,1,1),
('Zucchini Frites','Zucchini Fries','Zucchini-Pommes','Knapperige gepaneerde courgettestaafjes. Lekker met Griekse Yoghurt Ranch.','Crispy breaded zucchini sticks. Best with Greek Yogurt Ranch.','Knusprige panierte Zucchini-Sticks. Am besten mit griechischem Joghurt-Ranch.',4.50,'sides',190,0,1,2),
('Falafel Bites (5 stuks)','Baked Falafel Bites - 5pcs','Gebackene Falafel-Bites - 5 Stück','Vijf sappige gebakken falafel bites.','Five juicy baked falafel bites, perfect as a snack.','Fünf saftige gebackene Falafel-Bites.',5.00,'sides',230,1,1,3),
('Mini Groenteschotel & Hummus','Mini Veggie Platter & Hummus','Mini-Gemüseplatte & Hummus','Verse bleekselderij, worteltjes en komkommer met romige hummus.','Fresh crunch: celery, carrots, and cucumber with creamy hummus.','Staudensellerie, Karotten und Gurke mit cremigem Hummus.',4.00,'sides',160,1,1,4),
('Klassieke Hummus','Classic Hummus','Klassischer Hummus','Romige huisgemaakte hummus.','Creamy homemade hummus.','Cremiger hausgemachter Hummus.',1.00,'dips',120,1,1,1),
('Avocado Limoen Crema','Avocado Lime Crema','Avocado-Limetten-Crema','Frisse avocadosaus met limoen.','Fresh avocado sauce with lime.','Frische Avocadosauce mit Limette.',1.00,'dips',110,1,1,2),
('Griekse Yoghurt Ranch','Greek Yogurt Ranch','Griechischer Joghurt Ranch','Kruidige ranch op basis van Griekse yoghurt.','Herb ranch based on Greek yogurt.','Kräutiger Ranch auf Basis von griechischem Joghurt.',1.00,'dips',90,0,1,3),
('Pittige Sriracha Mayo','Spicy Sriracha Mayo','Scharfe Sriracha-Mayo','Pittige srirachama-yonaise.','Spicy sriracha mayonnaise.','Würzige Sriracha-Mayonnaise.',1.00,'dips',180,1,1,4),
('Pinda Satésaus','Peanut Satay Sauce','Erdnuss-Satay-Sauce','Rijke, romige pinda-satésaus.','Rich, creamy peanut satay sauce.','Reichhaltige, cremige Erdnuss-Satay-Sauce.',1.00,'dips',200,1,1,5),
('Green Glow Smoothie','Green Glow Smoothie','Green Glow Smoothie','Spinazie, ananas, komkommer en kokoswater.','Spinach, pineapple, cucumber, and coconut water.','Spinat, Ananas, Gurke und Kokoswasser.',3.50,'dranken',120,1,1,1),
('Iced Matcha Latte','Iced Matcha Latte','Iced Matcha Latte','Licht gezoete matcha-groene thee met amandelmelk.','Lightly sweetened matcha green tea with almond milk.','Leicht gesüßter Matcha-Grüntee mit Mandelmilch.',3.00,'dranken',90,1,1,2),
('Vers Infusiewater','Fruit-Infused Water','Fruchtinfusionswasser','Keuze uit citroen-munt, aardbei-basilicum of komkommer-limoen.','Choice of lemon-mint, strawberry-basil, or cucumber-lime.','Wahl aus Zitronen-Minze, Erdbeere-Basilikum oder Gurken-Limette.',1.50,'dranken',0,1,1,3),
('Berry Blast Smoothie','Berry Blast Smoothie','Berry Blast Smoothie','Romige mix van aardbeien, bosbessen en frambozen met amandelmelk.','A creamy blend of strawberries, blueberries, and raspberries with almond milk.','Eine cremige Mischung aus Erdbeeren, Heidelbeeren und Himbeeren mit Mandelmilch.',3.80,'dranken',140,1,1,4),
('Citrus Cooler','Citrus Cooler','Citrus Cooler','Verfrissende mix van sinaasappelsap, bruisend water en een vleugje limoen.','A refreshing mix of orange juice, sparkling water, and a hint of lime.','Eine erfrischende Mischung aus Orangensaft, Sprudelwasser und einem Hauch Limette.',3.00,'dranken',90,1,1,5);
