<?php
// deze functie haalt de producten op uit de database
// als de database er niet is gebruik ik de vaste lijst hieronder
function menu_ophalen(string $lang): array {
    require_once __DIR__ . '/../config/database.php';
    $conn = db_verbinden();

    $naam_kolom = "naam_{$lang}";
    $omsch_kolom = "beschrijving_{$lang}";

    if ($conn) {
        $sql = "SELECT id, {$naam_kolom} AS naam, {$omsch_kolom} AS beschrijving,
                       prijs, categorie, kcal, is_vegan, is_vegetarisch
                FROM producten WHERE actief = 1
                ORDER BY FIELD(categorie,'ontbijt','bowls','handhelds','sides','dips','dranken'), naam ASC";
        $res = $conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $producten = [];
            while ($r = $res->fetch_assoc()) {
                $producten[] = [
                    'id'           => (int)$r['id'],
                    'naam'         => $r['naam'],
                    'beschrijving' => $r['beschrijving'],
                    'prijs'        => (float)$r['prijs'],
                    'categorie'    => $r['categorie'],
                    'kcal'         => (int)$r['kcal'],
                    'is_vegan'     => (bool)$r['is_vegan'],
                    'is_vegetarisch'=> (bool)$r['is_vegetarisch'],
                ];
            }
            $conn->close();
            return $producten;
        }
        $conn->close();
    }

    // als de database niet werkt gebruik ik deze lijst
    return menu_hardcoded($lang);
}

function menu_hardcoded(string $lang): array {
    $data = [
        // ontbijt producten
        ['id'=>1,'categorie'=>'ontbijt','prijs'=>7.50,'kcal'=>320,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Morning Boost Açaí Bowl','en'=>'Morning Boost Açaí Bowl','de'=>'Morning Boost Açaí Bowl'],
         'beschrijving'=>['nl'=>'Gekoeld açaí met banaan, knapperige granola, chiazaad en kokos.','en'=>'Chilled açaí and banana topped with crunchy granola, chia seeds, and coconut.','de'=>'Gekühltes Açaí mit Banane, knusprigem Granola, Chiasamen und Kokos.']],
        ['id'=>2,'categorie'=>'ontbijt','prijs'=>6.50,'kcal'=>280,'is_vegan'=>false,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'The Garden Breakfast Wrap','en'=>'The Garden Breakfast Wrap','de'=>'The Garden Breakfast Wrap'],
         'beschrijving'=>['nl'=>'Volkorenwrap met roerei, babyspinazie en yoghurt-kruidentzaus.','en'=>'Whole-grain wrap with fluffy scrambled eggs, baby spinach, and yogurt-herb sauce.','de'=>'Vollkornwrap mit Rührei, Babyspinat und Joghurt-Kräutersauce.']],
        ['id'=>3,'categorie'=>'ontbijt','prijs'=>5.00,'kcal'=>240,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Peanut Butter & Cacao Toast','en'=>'Peanut Butter & Cacao Toast','de'=>'Erdnussbutter & Kakao Toast'],
         'beschrijving'=>['nl'=>'Zuurdesemtoast met 100% pindakaas, banaan en cacaonibs.','en'=>'Sourdough toast with 100% natural peanut butter, banana, and cacao nibs.','de'=>'Sauerteigtoast mit 100% Erdnussbutter, Banane und Kakaonibs.']],
        ['id'=>4,'categorie'=>'ontbijt','prijs'=>5.50,'kcal'=>290,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Overnight Oats: Appeltaart Stijl','en'=>'Overnight Oats: Apple Pie Style','de'=>'Overnight Oats: Apfelkuchen-Stil'],
         'beschrijving'=>['nl'=>'Havermout in amandelmelk met geraspte appel, kaneel en walnoten.','en'=>'Oats soaked in almond milk with grated apple, cinnamon, and crushed walnuts.','de'=>'Haferflocken in Mandelmilch mit geriebenem Apfel, Zimt und Walnüssen.']],
        // lunch en diner bowls
        ['id'=>5,'categorie'=>'bowls','prijs'=>10.50,'kcal'=>480,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Tofu Power Tahini Bowl','en'=>'Tofu Power Tahini Bowl','de'=>'Tofu Power Tahini Bowl'],
         'beschrijving'=>['nl'=>'Driekleurige quinoa, esdoorntofu, zoete aardappel en boerenkool met tahinidressing.','en'=>'Tri-color quinoa, maple-glazed tofu, roasted sweet potatoes, and kale with tahini dressing.','de'=>'Dreifarbige Quinoa, Ahorn-Tofu, geröstete Süßkartoffeln und Grünkohl mit Tahini-Dressing.']],
        ['id'=>6,'categorie'=>'bowls','prijs'=>9.50,'kcal'=>310,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'The Supergreen Harvest','en'=>'The Supergreen Harvest','de'=>'The Supergreen Harvest'],
         'beschrijving'=>['nl'=>'Gemasseerde boerenkool, edamame, avocado, komkommer en pompoenpitten.','en'=>'Massaged kale, edamame, avocado, cucumber, and toasted pumpkin seeds with lemon-olive oil.','de'=>'Massierter Grünkohl, Edamame, Avocado, Gurke und Kürbiskerne mit Zitronen-Olivenöl.']],
        ['id'=>7,'categorie'=>'bowls','prijs'=>10.00,'kcal'=>440,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Mediterranean Falafel Bowl','en'=>'Mediterranean Falafel Bowl','de'=>'Mediterrane Falafel Bowl'],
         'beschrijving'=>['nl'=>'Gebakken falafel, hummus, ingelegde rode ui, cherrytomaatjes en komkommer.','en'=>'Baked falafel, hummus, pickled red onions, cherry tomatoes, and cucumber on greens.','de'=>'Gebackener Falafel, Hummus, eingelegte rote Zwiebeln, Kirschtomaten und Gurke.']],
        ['id'=>8,'categorie'=>'bowls','prijs'=>11.00,'kcal'=>500,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Warm Teriyaki Tempeh Bowl','en'=>'Warm Teriyaki Tempeh Bowl','de'=>'Warme Teriyaki Tempeh Bowl'],
         'beschrijving'=>['nl'=>'Gestoomde zilvervliesrijst, tempeh, broccoli en wortel met gember-sojasaus.','en'=>'Steamed brown rice, seared tempeh, broccoli, and carrots with a ginger-soy glaze.','de'=>'Gedämpfter Vollkornreis, Tempeh, Brokkoli und Karotten mit Ingwer-Sojaglasur.']],
        // wraps en sandwiches
        ['id'=>9,'categorie'=>'handhelds','prijs'=>8.50,'kcal'=>410,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Zesty Chickpea Hummus Wrap','en'=>'Zesty Chickpea Hummus Wrap','de'=>'Würziger Kichererbsen-Hummus-Wrap'],
         'beschrijving'=>['nl'=>'Gekruide kikkererwten, geraspte wortel, sla en hummus in een volkorenwrap.','en'=>'Spiced chickpeas, shredded carrots, crisp lettuce, and signature hummus in a whole-wheat wrap.','de'=>'Gewürzte Kichererbsen, geraspelte Karotten, knackiger Salat und Hummus in einem Vollkornwrap.']],
        ['id'=>10,'categorie'=>'handhelds','prijs'=>9.00,'kcal'=>460,'is_vegan'=>false,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Avocado & Halloumi Toastie','en'=>'Avocado & Halloumi Toastie','de'=>'Avocado & Halloumi Toastie'],
         'beschrijving'=>['nl'=>'Gegrilde halloumi, gepureerde avocado en chilivlokken op meerzadenbrood.','en'=>'Grilled halloumi cheese, smashed avocado, and chili flakes on thick-cut multi-grain bread.','de'=>'Gegrillter Halloumi, zerdrückte Avocado und Chiliflocken auf Mehrkornbrot.']],
        ['id'=>11,'categorie'=>'handhelds','prijs'=>7.50,'kcal'=>350,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Smoky BBQ Jackfruit Slider','en'=>'Smoky BBQ Jackfruit Slider','de'=>'Smoky BBQ Jackfruit Slider'],
         'beschrijving'=>['nl'=>'Pulled jackfruit in BBQ-saus met paarse coleslaw op een vegan briochebroodje.','en'=>'Pulled jackfruit in BBQ sauce with a crunchy purple slaw on a vegan brioche bun.','de'=>'Pulled Jackfruit in BBQ-Sauce mit knusprigem lila Coleslaw auf einem veganen Briochebrötchen.']],
        // bijgerechten
        ['id'=>12,'categorie'=>'sides','prijs'=>4.50,'kcal'=>260,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Zoete Aardappel Wedges','en'=>'Oven-Baked Sweet Potato Wedges','de'=>'Ofengebackene Süßkartoffelwedges'],
         'beschrijving'=>['nl'=>'Gekruid met gerookt paprikapoeder. Lekker met Avocado Limoen Crema.','en'=>'Seasoned with smoked paprika. Best with Avocado Lime Dip.','de'=>'Mit geräuchertem Paprika gewürzt. Am besten mit Avocado-Limetten-Dip.']],
        ['id'=>13,'categorie'=>'sides','prijs'=>4.50,'kcal'=>190,'is_vegan'=>false,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Zucchini Frites','en'=>'Zucchini Fries','de'=>'Zucchini-Pommes'],
         'beschrijving'=>['nl'=>'Knapperige gepaneerde courgettestaafjes. Lekker met Griekse Yoghurt Ranch.','en'=>'Crispy breaded zucchini sticks. Best with Greek Yogurt Ranch.','de'=>'Knusprige panierte Zucchini-Sticks. Am besten mit griechischem Joghurt-Ranch.']],
        ['id'=>14,'categorie'=>'sides','prijs'=>5.00,'kcal'=>230,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Falafel Bites (5 stuks)','en'=>'Baked Falafel Bites — 5pcs','de'=>'Gebackene Falafel-Bites — 5 Stück'],
         'beschrijving'=>['nl'=>'Vijf sappige gebakken falafel bites, perfect als snack.','en'=>'Five juicy baked falafel bites, perfect as a snack.','de'=>'Fünf saftige gebackene Falafel-Bites, perfekt als Snack.']],
        ['id'=>15,'categorie'=>'sides','prijs'=>4.00,'kcal'=>160,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Mini Groenteschotel & Hummus','en'=>'Mini Veggie Platter & Hummus','de'=>'Mini-Gemüseplatte & Hummus'],
         'beschrijving'=>['nl'=>'Verse bleekselderij, worteltjes en komkommer met romige hummus.','en'=>'Fresh crunch: celery, carrots, and cucumber with creamy hummus.','de'=>'Frischer Knack: Staudensellerie, Karotten und Gurke mit cremigem Hummus.']],
        // dips
        ['id'=>16,'categorie'=>'dips','prijs'=>1.00,'kcal'=>120,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Klassieke Hummus','en'=>'Classic Hummus','de'=>'Klassischer Hummus'],
         'beschrijving'=>['nl'=>'Romige huisgemaakte hummus.','en'=>'Creamy homemade hummus.','de'=>'Cremiger hausgemachter Hummus.']],
        ['id'=>17,'categorie'=>'dips','prijs'=>1.00,'kcal'=>110,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Avocado Limoen Crema','en'=>'Avocado Lime Crema','de'=>'Avocado-Limetten-Crema'],
         'beschrijving'=>['nl'=>'Frisse avocadosaus met limoen.','en'=>'Fresh avocado sauce with lime.','de'=>'Frische Avocadosauce mit Limette.']],
        ['id'=>18,'categorie'=>'dips','prijs'=>1.00,'kcal'=>90,'is_vegan'=>false,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Griekse Yoghurt Ranch','en'=>'Greek Yogurt Ranch','de'=>'Griechischer Joghurt Ranch'],
         'beschrijving'=>['nl'=>'Kruidige ranch op basis van Griekse yoghurt.','en'=>'Herb ranch based on Greek yogurt.','de'=>'Kräutiger Ranch auf Basis von griechischem Joghurt.']],
        ['id'=>19,'categorie'=>'dips','prijs'=>1.00,'kcal'=>180,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Pittige Sriracha Mayo','en'=>'Spicy Sriracha Mayo','de'=>'Scharfe Sriracha-Mayo'],
         'beschrijving'=>['nl'=>'Pittige srirachama-yonaise.','en'=>'Spicy sriracha mayonnaise.','de'=>'Würzige Sriracha-Mayonnaise.']],
        ['id'=>20,'categorie'=>'dips','prijs'=>1.00,'kcal'=>200,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Pinda Satésaus','en'=>'Peanut Satay Sauce','de'=>'Erdnuss-Satay-Sauce'],
         'beschrijving'=>['nl'=>'Rijke, romige pinda-satésaus.','en'=>'Rich, creamy peanut satay sauce.','de'=>'Reichhaltige, cremige Erdnuss-Satay-Sauce.']],
        // dranken
        ['id'=>21,'categorie'=>'dranken','prijs'=>3.50,'kcal'=>120,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Green Glow Smoothie','en'=>'Green Glow Smoothie','de'=>'Green Glow Smoothie'],
         'beschrijving'=>['nl'=>'Spinazie, ananas, komkommer en kokoswater.','en'=>'Spinach, pineapple, cucumber, and coconut water.','de'=>'Spinat, Ananas, Gurke und Kokoswasser.']],
        ['id'=>22,'categorie'=>'dranken','prijs'=>3.00,'kcal'=>90,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Iced Matcha Latte','en'=>'Iced Matcha Latte','de'=>'Iced Matcha Latte'],
         'beschrijving'=>['nl'=>'Licht gezoete matcha-groene thee met amandelmelk.','en'=>'Lightly sweetened matcha green tea with almond milk.','de'=>'Leicht gesüßter Matcha-Grüntee mit Mandelmilch.']],
        ['id'=>23,'categorie'=>'dranken','prijs'=>1.50,'kcal'=>0,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Vers Infusiewater','en'=>'Fruit-Infused Water','de'=>'Fruchtinfusionswasser'],
         'beschrijving'=>['nl'=>'Keuze uit citroen-munt, aardbei-basilicum of komkommer-limoen.','en'=>'Choice of lemon-mint, strawberry-basil, or cucumber-lime.','de'=>'Wahl aus Zitronen-Minze, Erdbeere-Basilikum oder Gurken-Limette.']],
        ['id'=>24,'categorie'=>'dranken','prijs'=>3.80,'kcal'=>140,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Berry Blast Smoothie','en'=>'Berry Blast Smoothie','de'=>'Berry Blast Smoothie'],
         'beschrijving'=>['nl'=>'Romige mix van aardbeien, bosbessen en frambozen met amandelmelk.','en'=>'A creamy blend of strawberries, blueberries, and raspberries with almond milk.','de'=>'Eine cremige Mischung aus Erdbeeren, Heidelbeeren und Himbeeren mit Mandelmilch.']],
        ['id'=>25,'categorie'=>'dranken','prijs'=>3.00,'kcal'=>90,'is_vegan'=>true,'is_vegetarisch'=>true,
         'naam'=>['nl'=>'Citrus Cooler','en'=>'Citrus Cooler','de'=>'Citrus Cooler'],
         'beschrijving'=>['nl'=>'Verfrissende mix van sinaasappelsap, bruisend water en een vleugje limoen.','en'=>'A refreshing mix of orange juice, sparkling water, and a hint of lime.','de'=>'Eine erfrischende Mischung aus Orangensaft, Sprudelwasser und einem Hauch Limette.']],
    ];

    // hier pak ik alleen de naam en beschrijving voor de gekozen taal
    return array_map(function($p) use ($lang) {
        $p['naam']         = $p['naam'][$lang] ?? $p['naam']['nl'];
        $p['beschrijving'] = $p['beschrijving'][$lang] ?? $p['beschrijving']['nl'];
        return $p;
    }, $data);
}

// zet de producten per categorie in een array
function menu_gecategoriseerd(array $producten): array {
    $per_cat = [];
    foreach ($producten as $p) {
        $per_cat[$p['categorie']][] = $p;
    }
    return $per_cat;
}

// geeft alleen de dips terug, die gebruik ik op de productpagina
function menu_dips(array $producten): array {
    return array_values(array_filter($producten, fn($p) => $p['categorie'] === 'dips'));
}

// zoek een enkel product op basis van het id
function product_ophalen(int $id, array $producten): ?array {
    foreach ($producten as $p) {
        if ($p['id'] === $id) return $p;
    }
    return null;
}
