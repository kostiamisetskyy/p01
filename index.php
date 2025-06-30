<?php
$dbFile = 'database.json';
$menuTitle = 'Menu de la semaine'; // Default title
$dishes = [];
$weeklyMenu = [];
$days = [
    'tuesday' => 'MARDI',
    'wednesday' => 'MERCREDI',
    'thursday' => 'JEUDI',
    'friday' => 'VENDREDI'
];

if (file_exists($dbFile)) {
    $data = json_decode(file_get_contents($dbFile), true);
    // Check if json_decode was successful and data exists
    if (is_array($data)) {
        $menuTitle = $data['menuTitle'] ?? $menuTitle;
        $dishes = $data['dishes'] ?? [];
        $weeklyMenu = $data['weeklyMenu'] ?? [];
    }
}

function get_dish_by_id($id, $dishes) {
    foreach ($dishes as $dish) {
        if (isset($dish['id']) && $dish['id'] === $id) {
            return $dish;
        }
    }
    return null;
}
?>
<!doctype html>
<html
    data-bs-theme="light"
    lang="fr"
    style="
        backdrop-filter: contrast(100%);
        -webkit-backdrop-filter: contrast(100%);
    "
>
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        />
        <title>BKK Street Food Lausanne - Livraison de Plats Thaïlandais</title>
        <link rel="canonical" href="https://bkkstreetfood.ch/" />
        <meta property="og:url" content="https://bkkstreetfood.ch/" />
        <meta
            name="twitter:image"
            content="https://bkkstreetfood.ch/assets/img/bkk-1.jpg"
        />
        <meta name="twitter:card" content="summary_large_image" />
        <meta property="og:type" content="website" />
        <meta
            name="description"
            content="BKK Street Food Lausanne vous propose une authentique cuisine thaïlandaise en livraison. Découvrez nos plats faits maison, préparés par un chef formé en Thaïlande."
        />
        <meta
            property="og:image"
            content="https://bkkstreetfood.ch/assets/img/bkk-1.jpg"
        />
        <meta name="twitter:title" content="BKK Street Food" />
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Restaurant",
                "name": "BKK Street Food",
                "url": "https://bkkstreetfood.ch",
                "description": "Découvrez BKK Street Food à Lausanne. Commandez en ligne nos plats thaïlandais authentiques, préparés avec des produits frais. Livraison du mardi au vendredi midi.",
                "servesCuisine": "Thai",
                "image": "https://bkkstreetfood.ch/assets/img/bkk-1.jpg",
                "telephone": "+41767629116",
                "address": {
                  "@type": "PostalAddress",
                  "addressLocality": "Lausanne",
                  "postalCode": "1003, 1004, 1005, 1006",
                  "addressCountry": "CH"
                },
                "priceRange": "23 CHF",
                "openingHoursSpecification": [
                  {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": [
                      "Tuesday",
                      "Wednesday",
                      "Thursday",
                      "Friday"
                    ],
                    "opens": "11:00",
                    "closes": "12:00"
                  }
                ]
            }
        </script>
        <link
            rel="icon"
            type="image/png"
            sizes="500x500"
            href="assets/img/WhatsApp-Image-2025-04-23-at-1.45.43 PM-(1).png"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        />
        <link
            rel="manifest"
            href="manifest.json"
            crossorigin="use-credentials"
        />
        <link rel="stylesheet" href="assets/css/styles.min.css" />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
        />
    </head>
    <body>
        <!-- Start: 1 Row 1 Column -->
        <div class="container" style="color: var(--bs-primary)">
            <div class="row">
                <div class="col mx-auto" style="text-align: center">
                    <h1>
                        <a href="index.php"
                            ><img
                                class="img-fluid pb-md-0 mb-md-0 mt-5"
                                src="assets/img/LOGO-BKK-png.png"
                                loading="lazy"
                                width="25%"
                                alt="BKK Street Food Logo"
                        /></a>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End: 1 Row 1 Column --><!-- Start: 1 Row 1 Column -->
        <div class="container">
            <div class="row">
                <div
                    class="col-md-12 col-lg-10 offset-lg-1 d-xxl-flex justify-content-xxl-center"
                >
                    <img
                        class="d-lg-flex align-items-lg-center pt-md-4"
                        alt="Plat thaïlandais de BKK Street Food"
                        data-aos="fade"
                        src="assets/img/bkk-1.jpg"
                        width="100%"
                        style="margin-left: 0px; padding-top: 20px"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>
        <!-- End: 1 Row 1 Column --><!-- Start: Features Image -->
        <div
            class="container py-4 py-xl-5"
            style="
                margin-bottom: 24px;
                border-style: none;
                border-color: var(--bs-primary);
            "
        >
            <div class="row row-cols-1 row-cols-md-2">
                <div class="col">
                    <img
                        class="rounded w-100 h-100 fit-cover"
                        alt="Pô Suchart Gygax cuisine"
                        style="min-height: 300px"
                        src="assets/img/bkk22.jpg"
                        loading="lazy"
                    />
                </div>
                <div class="col d-flex flex-column justify-content-center p-4">
                    <div
                        class="text-center text-md-start d-flex flex-column align-items-center align-items-md-start mb-5"
                        style="margin-bottom: 17px"
                    >
                        <div style="margin-bottom: -50px">
                            <h4>
                                La meilleure cuisine de rue de Thaïlande livrée à votre porte à Lausanne,<br />du mardi
                                au vendredi midi !
                            </h4>
                            <p></p>
                        </div>
                    </div>
                    <div
                        class="text-center text-md-start d-flex flex-column align-items-center align-items-md-start mb-5"
                    >
                        <div>
                            <p
                                class="text-start text-sm-start text-md-start text-lg-start text-xl-start text-xxl-start"
                            >
                                Depuis cinq ans, BKK Street Food Lausanne partage avec
                                passion l'authenticité de la cuisine
                                thaïlandaise.
                            </p>
                            <p
                                class="text-start text-sm-start text-md-start text-lg-start text-xl-start text-xxl-start"
                            >
                                Fondé par Pô Suchart Gygax, <br />BKK est le
                                fruit du savoir-faire d'un chef autodidacte
                                d'origine thaïlandaise, <br />formé par le Chef
                                Narong du Pla-tu-thong, ancien cuisinier de la
                                famille royale thaïlandaise. <br />Il a ensuite
                                perfectionné son art dans<br />deux écoles en
                                Thaïlande, dont l'une spécialisée dans la
                                formation de chefs étoilés.
                            </p>
                            <p
                                class="text-start text-sm-start text-md-start text-lg-start text-xl-start text-xxl-start"
                            >
                                Nos plats sont faits maison, <br />préparés avec
                                des produits frais,<br />et surtout amour et
                                passion. <br />Nos recettes s'inspirent de la
                                tradition thaïlandaise, tout en intégrant des
                                touches modernes pour une évolution savoureuse
                                des saveurs d'antan.
                            </p>
                            <p
                                class="text-start text-sm-start text-md-start text-lg-start text-xl-start text-xxl-start"
                            >
                                Dès maintenant, nous livrons votre dîner, pour
                                que chaque midi devienne <br />une véritable
                                expérience culinaire.
                            </p>
                            <p
                                class="text-start text-sm-start text-lg-start text-xl-start text-xxl-start"
                                style="font-weight: bold"
                            >
                                Commandez et savourez l'équilibre parfait
                                entre<br />tradition et modernité !
                            </p>
                        </div>
                    </div>
                    <div
                        class="text-center text-md-start d-flex flex-column align-items-center align-items-md-start"
                    >
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End: Features Image --><!-- Start: 1 Row 1 Column -->
        <div
            class="container"
            style="border-style: none; border-color: var(--bs-primary)"
        >
            <h2
                style="
                    text-align: center;
                    margin-top: 10px;
                    margin-bottom: 12px;
                "
            >
                <?php echo htmlspecialchars($menuTitle); ?>
            </h2>
            <h6
                style="text-align: center; color: var(--bs-emphasis-color)"
            ></h6>
            <div class="row">
                <div class="col-md-12">
                    <!-- Start: Hero Carousel -->
                    <div
                        class="carousel slide"
                        data-bs-ride="carousel"
                        id="carousel-1"
                        style="height: 600px; margin-bottom: 24px"
                    >
                        <div class="carousel-inner h-100">
                            <?php
                            if (!empty($weeklyMenu['weekOff'])) {
                            ?>
                                <div class="carousel-item active h-100">
                                    <img class="img-fluid w-100 d-block position-absolute h-100 fit-cover" src="assets/img/bkk-1.jpg" alt="Service fermé cette semaine" style="z-index: -1;" loading="eager">
                                    <div class="container d-flex flex-column justify-content-center h-100">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4 offset-md-2 mx-auto" style="background: rgba(255,255,255,0.8);">
                                                <div style="max-width: 350px;">
                                                    <h2 class="text-uppercase fw-bold">SERVICE FERMÉ</h2>
                                                    <p class="my-3" style="margin-bottom: 16px;">Nous sommes exceptionnellement fermés cette semaine. De retour bientôt avec de nouvelles saveurs !</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } elseif (!empty($weeklyMenu['week'])) {
                                $active = true;
                                foreach ($weeklyMenu['week'] as $dayKey => $dayData) {
                                    $dish = isset($dayData['dishId']) ? get_dish_by_id($dayData['dishId'], $dishes) : null;
                                    $dayName = $days[$dayKey] ?? strtoupper($dayKey);
                            ?>
                            <div class="carousel-item <?php if ($active) { echo 'active'; $active = false; } ?> h-100">
                                <?php if ($dish && empty($dayData['off'])): ?>
                                    <img class="img-fluid w-100 d-block position-absolute h-100 fit-cover" src="<?php echo htmlspecialchars($dish['image']); ?>" alt="<?php echo htmlspecialchars($dish['name']); ?>" style="z-index: -1;" loading="eager">
                                    <div class="container d-flex flex-column justify-content-center h-100">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4 offset-md-2 mx-auto" style="background: rgba(255,255,255,0.8);">
                                                <div style="max-width: 350px;">
                                                    <h3 class="text-uppercase fw-bold"><?php echo htmlspecialchars($dayName); ?></h3>
                                                    <h5 class="text-uppercase fw-bold"><?php echo htmlspecialchars($dish['name']); ?><?php if ($dish['spicy']) echo ' 🌶️'; ?></h5>
                                                    <p class="my-3" style="margin-bottom: 16px;"><strong><?php echo htmlspecialchars($dish['description']); ?></strong></p>
                                                    <?php if (!empty($dish['veggie'])): ?>
                                                    <p class="my-3" style="color: var(--bs-green);"><strong><?php echo htmlspecialchars($dish['veggie']); ?></strong></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: // Day is off or no dish assigned ?>
                                    <img class="img-fluid w-100 d-block position-absolute h-100 fit-cover" src="assets/img/bkk22.jpg" alt="Service fermé aujourd'hui" style="z-index: -1;" loading="eager">
                                     <div class="container d-flex flex-column justify-content-center h-100">
                                        <div class="row">
                                            <div class="col-md-6 col-xl-4 offset-md-2 mx-auto" style="background: rgba(255,255,255,0.8);">
                                                <div style="max-width: 350px;">
                                                    <h3 class="text-uppercase fw-bold"><?php echo htmlspecialchars($dayName); ?></h3>
                                                    <p class="my-3" style="margin-bottom: 16px;"><strong>Pas de service aujourd'hui. Rendez-vous demain pour un nouveau plat délicieux !</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                                } // end foreach
                            } // end if
                            ?>
                        </div>
                        <div>
                            <!-- Start: Previous --><a
                                class="carousel-control-prev"
                                href="#carousel-1"
                                role="button"
                                data-bs-slide="prev"
                                style="
                                    backdrop-filter: blur(5px);
                                    -webkit-backdrop-filter: blur(5px);
                                "
                                ><span class="carousel-control-prev-icon"></span
                                ><span class="visually-hidden"
                                    >Previous</span
                                ></a
                            ><!-- End: Previous --><!-- Start: Next --><a
                                class="carousel-control-next"
                                href="#carousel-1"
                                role="button"
                                data-bs-slide="next"
                                style="
                                    backdrop-filter: blur(5px);
                                    -webkit-backdrop-filter: blur(5px);
                                "
                                ><span class="carousel-control-next-icon"></span
                                ><span class="visually-hidden">Next</span></a
                            ><!-- End: Next -->
                        </div>
                        <div
                            class="carousel-indicators"
                            style="
                                filter: blur(0px);
                                backdrop-filter: blur(0px);
                                -webkit-backdrop-filter: blur(0px);
                            "
                        >
                        <?php
                            if (empty($weeklyMenu['weekOff']) && !empty($weeklyMenu['week'])) {
                                $slideIndex = 0;
                                foreach ($weeklyMenu['week'] as $dayKey => $dayData) {
                            ?>
                            <button type="button" data-bs-target="#carousel-1" data-bs-slide-to="<?php echo $slideIndex; ?>" class="<?php if ($slideIndex === 0) echo 'active'; ?>"></button>
                            <?php
                                    $slideIndex++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- End: Hero Carousel -->
                </div>
            </div>
        </div>
        <!-- End: 1 Row 1 Column --><a
            href="https://www.mappingfestival.com"
            target="_blank"
        ></a
        ><!-- Start: 1 Row 1 Column -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Start: Contact Details -->
                    <section class="position-relative py-4 py-xl-5">
                        <div class="container position-relative">
                            <h2 style="text-align: center">Contact</h2>
                            <h6
                                style="
                                    text-align: center;
                                    color: var(--bs-emphasis-color);
                                "
                            >
                                - Offre de lancement -<br />pendant tout le mois
                                de juin le plat est à 23 CHF
                            </h6>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div
                                        class="d-flex flex-column justify-content-center align-items-start h-100"
                                    >
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-telephone"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Téléphone</h6>
                                                <a href="tel:+41767629116"
                                                    ><p
                                                        class="mb-0"
                                                        style="
                                                            color: var(
                                                                --bs-primary
                                                            );
                                                        "
                                                    >
                                                        +41 (0) 76 762 91 16
                                                    </p></a
                                                >
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-envelope"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Email</h6>
                                                <a
                                                    href="mailto:Info@bkkstreetfood.ch"
                                                    ><p class="mb-0">
                                                        Info@bkkstreetfood.ch
                                                    </p></a
                                                >
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-instagram"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Instagram</h6>
                                                <a
                                                    href="https://www.instagram.com/bkk_street_food/"
                                                    target="_blank"
                                                    ><p class="mb-0">
                                                        @bkk_street_food
                                                    </p></a
                                                >
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-pin"
                                                    style="
                                                        background: var(
                                                            --bs-secondary
                                                        );
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A5.921 5.921 0 0 1 5 6.708V2.277a2.77 2.77 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354zm1.58 1.408-.002-.001.002.001m-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a4.922 4.922 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a4.915 4.915 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.775 1.775 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14c.06.1.133.191.214.271a1.78 1.78 0 0 0 .37.282"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">
                                                    Zones de livraison
                                                </h6>
                                                <p class="mb-0">
                                                    1003, 1004, 1005, 1006
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    viewBox="0 0 16 16"
                                                    fill="currentColor"
                                                    class="bi bi-patch-check-fll"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984a.5.5 0 0 0-.708-.708L7 8.793 5.854 7.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Commandes</h6>
                                                <p class="mb-0">
                                                    Jusqu'à 13h la veille pour
                                                    une livraison le
                                                    lendemain&nbsp;
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-backpack4-fill"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M8 0a2 2 0 0 0-2 2H3.5a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h4v.5a.5.5 0 0 0 1 0V7h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H10a2 2 0 0 0-2-2m1 2a1 1 0 0 0-2 0zm-4 9v2h6v-2h-1v.5a.5.5 0 0 1-1 0V11z"
                                                    ></path>
                                                    <path
                                                        d="M14 7.599A2.986 2.986 0 0 1 12.5 8H9.415a1.5 1.5 0 0 1-2.83 0H3.5A2.986 2.986 0 0 1 2 7.599V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Livraisons</h6>
                                                <p class="mb-0">
                                                    Entre 11:00 et 12:00
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex align-items-center p-3"
                                        >
                                            <div
                                                class="bs-icon-md bs-icon-rounded bs-icon-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon"
                                                style="
                                                    background: var(
                                                        --bs-secondary
                                                    );
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="1em"
                                                    height="1em"
                                                    fill="currentColor"
                                                    viewBox="0 0 16 16"
                                                    class="bi bi-currency-dollar"
                                                    style="
                                                        color: var(
                                                            --bs-primary-border-subtle
                                                        );
                                                    "
                                                >
                                                    <path
                                                        d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mb-0">Prix</h6>
                                                <p class="mb-0">
                                                    <span
                                                        style="
                                                            text-decoration: line-through;
                                                        "
                                                        >25</span
                                                    >&nbsp;23 CHF le plat
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-5 col-xl-4">
                                    <div></div>
                                    <img
                                        alt="Chef Pô Suchart Gygax de BKK Street Food"
                                        src="assets/img/WhatsApp%20Image%202025-04-23%20at%201.45.43 PM.jpeg"
                                        width="100%"
                                        loading="eager"
                                    />
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End: Contact Details -->
                </div>
            </div>
        </div>
        <!-- End: 1 Row 1 Column -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 style="text-align: center; margin-top: 20px;">
                        BKK Street Food: Votre traiteur thaïlandais à Lausanne
                    </h2>
                    <p style="text-align: center;">
                        BKK Street Food est votre service de traiteur thaïlandais de confiance, idéalement situé pour servir la communauté de Lausanne. Notre cuisine est un hommage à la gastronomie de rue de Bangkok, offrant des saveurs authentiques et des plats préparés avec soin. Nous sommes fiers de notre héritage et de notre capacité à fournir une expérience culinaire exceptionnelle à nos clients de Lausanne et des environs.
                    </p>
                </div>
            </div>
        </div>
        <!-- Start: 1 Row 1 Column -->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Start: Footer Basic -->
                    <footer class="text-center">
                        <div class="container text-muted py-4 py-lg-5">
                            <ul class="list-inline"></ul>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a
                                        href="https://www.instagram.com/bkk_street_food/"
                                        target="_blank"
                                        rel="external"
                                        ><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="1em"
                                            height="1em"
                                            fill="currentColor"
                                            viewBox="0 0 16 16"
                                            class="bi bi-instagram"
                                            style="font-size: 34px"
                                        >
                                            <path
                                                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"
                                            ></path></svg
                                    ></a>
                                </li>
                            </ul>
                            <p class="mb-0">
                                Copyright © 2025 BKK Street Food
                            </p>
                            <p class="mb-0">
                                Illustration : Olivia Hadorn / Web design :
                                Kostyantyn Misetskyy
                            </p>
                        </div>
                    </footer>
                    <!-- End: Footer Basic -->
                </div>
            </div>
        </div>
        <!-- End: 1 Row 1 Column -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
        <script src="assets/js/script.min.js"></script>
    </body>
</html>
