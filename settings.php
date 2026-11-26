<?php
// config/settings.php
// Vérifier si la session est déjà démarrée avant de l'appeler
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Langues disponibles
$available_languages = [
    'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'locale' => 'fr_FR'],
    'en' => ['name' => 'English', 'flag' => '🇬🇧', 'locale' => 'en_US'],
    'ar' => ['name' => 'العربية', 'flag' => '🇸🇦', 'locale' => 'ar_MA'],
    'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'locale' => 'es_ES'],
    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'locale' => 'de_DE'],
    'it' => ['name' => 'Italiano', 'flag' => '🇮🇹', 'locale' => 'it_IT']
];

// Devises disponibles
$available_currencies = [
    'MAD' => ['symbol' => 'DH', 'name' => 'Dirham marocain'],
    'EUR' => ['symbol' => '€', 'name' => 'Euro'],
    'USD' => ['symbol' => '$', 'name' => 'Dollar US'],
    'GBP' => ['symbol' => '£', 'name' => 'Livre sterling']
];

// Initialisation des paramètres
function initSettings() {
    global $available_languages, $available_currencies;
    
    // Initialiser les paramètres par défaut si non définis
    if (!isset($_SESSION['settings'])) {
        $_SESSION['settings'] = [];
    }
    
    // Langue
    if (!isset($_SESSION['settings']['language'])) {
        if (isset($_COOKIE['language']) && isset($available_languages[$_COOKIE['language']])) {
            $_SESSION['settings']['language'] = $_COOKIE['language'];
        } else {
            $_SESSION['settings']['language'] = 'fr';
        }
    }
    
    // Thème
    if (!isset($_SESSION['settings']['theme'])) {
        if (isset($_COOKIE['theme']) && in_array($_COOKIE['theme'], ['light', 'dark'])) {
            $_SESSION['settings']['theme'] = $_COOKIE['theme'];
        } else {
            $_SESSION['settings']['theme'] = 'light';
        }
    }
    // Devise
    if (!isset($_SESSION['settings']['currency'])) {
        if (isset($_COOKIE['currency']) && isset($available_currencies[$_COOKIE['currency']])) {
            $_SESSION['settings']['currency'] = $_COOKIE['currency'];
        } else {
            $_SESSION['settings']['currency'] = 'MAD';
        }
    }
    
    // Notifications
    if (!isset($_SESSION['settings']['notifications'])) {
        $_SESSION['settings']['notifications'] = [
            'email' => true,
            'push' => true,
            'promo' => false
        ];
    }
    
    // Fuseau horaire
    if (!isset($_SESSION['settings']['timezone'])) {
        if (isset($_COOKIE['timezone'])) {
            $_SESSION['settings']['timezone'] = $_COOKIE['timezone'];
        } else {
            $_SESSION['settings']['timezone'] = 'Africa/Casablanca';
        }
    }
}

// Appliquer les paramètres
function applySettings() {
    initSettings();
    
    // Appliquer le fuseau horaire
    if (isset($_SESSION['settings']['timezone'])) {
        date_default_timezone_set($_SESSION['settings']['timezone']);
    }
    
    // Appliquer la locale
    if (isset($_SESSION['settings']['language']) && isset($GLOBALS['available_languages'][$_SESSION['settings']['language']])) {
        $locale = $GLOBALS['available_languages'][$_SESSION['settings']['language']]['locale'];
        setlocale(LC_ALL, $locale);
        putenv("LC_ALL=$locale");
    }
}

// Fonction de traduction
function __($key) {
    $lang = $_SESSION['settings']['language'] ?? 'fr';
    $translations = getTranslations($lang);
    return $translations[$key] ?? $key;
}

// Obtenir les traductions
function getTranslations($lang) {
    $translations = [
        'fr' => [
            // Navigation
            'home' => 'Accueil',
            'shop' => 'Boutique',
            'cart' => 'Panier',
            'profile' => 'Profil',
            'dashboard' => 'Tableau de bord',
            'settings' => 'Paramètres',
            'logout' => 'Déconnexion',
            'login' => 'Connexion',
            
            // Paramètres
            'settings_title' => 'Paramètres',
            'language' => 'Langue',
            'choose_language' => 'Choisissez votre langue',
            'appearance' => 'Apparence',
            'theme' => 'Thème',
            'light_theme' => 'Clair',
            'dark_theme' => 'Sombre',
            'notifications' => 'Notifications',
            'email_notifications' => 'Notifications par email',
            'push_notifications' => 'Notifications push',
            'promo_notifications' => 'Offres promotionnelles',
            'preferences' => 'Préférences',
            'currency' => 'Devise',
            'timezone' => 'Fuseau horaire',
            'save_settings' => 'Sauvegarder les paramètres',
            'back' => 'Retour',
            'current_language' => 'Langue actuelle',
            
            // Messages
            'settings_saved' => 'Paramètres sauvegardés avec succès !',
            'error_saving' => 'Erreur lors de la sauvegarde',
            'language_changed' => 'Langue changée',
            
            // Produits
            'add_to_cart' => 'Ajouter au panier',
            'price' => 'Prix',
            'quantity' => 'Quantité',
            'total' => 'Total',
            'empty_cart' => 'Votre panier est vide',
            'checkout' => 'Valider la commande',
            
            // Autres
            'welcome' => 'Bienvenue',
            'search' => 'Rechercher',
            'categories' => 'Catégories',
            'contact' => 'Contact',
            'about' => 'À propos'
        ],
        'en' => [
            // Navigation
            'home' => 'Home',
            'shop' => 'Shop',
            'cart' => 'Cart',
            'profile' => 'Profile',
            'dashboard' => 'Dashboard',
            'settings' => 'Settings',
            'logout' => 'Logout',
            'login' => 'Login',
            
            // Paramètres
            'settings_title' => 'Settings',
            'language' => 'Language',
            'choose_language' => 'Choose your language',
            'appearance' => 'Appearance',
            'theme' => 'Theme',
            'light_theme' => 'Light',
            'dark_theme' => 'Dark',
            'notifications' => 'Notifications',
            'email_notifications' => 'Email notifications',
            'push_notifications' => 'Push notifications',
            'promo_notifications' => 'Promotional offers',
            'preferences' => 'Preferences',
            'currency' => 'Currency',
            'timezone' => 'Timezone',
            'save_settings' => 'Save settings',
            'back' => 'Back',
            'current_language' => 'Current language',
            
            // Messages
            'settings_saved' => 'Settings saved successfully!',
            'error_saving' => 'Error saving settings',
            'language_changed' => 'Language changed',
            
            // Produits
            'add_to_cart' => 'Add to cart',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'total' => 'Total',
            'empty_cart' => 'Your cart is empty',
            'checkout' => 'Checkout',
            
            // Autres
            'welcome' => 'Welcome',
            'search' => 'Search',
            'categories' => 'Categories',
            'contact' => 'Contact',
            'about' => 'About'
        ],
        'ar' => [
            // Navigation
            'home' => 'الرئيسية',
            'shop' => 'المتجر',
            'cart' => 'السلة',
            'profile' => 'الملف الشخصي',
            'dashboard' => 'لوحة التحكم',
            'settings' => 'الإعدادات',
            'logout' => 'تسجيل الخروج',
            'login' => 'تسجيل الدخول',
            
            // Paramètres
            'settings_title' => 'الإعدادات',
            'language' => 'اللغة',
            'choose_language' => 'اختر لغتك',
            'appearance' => 'المظهر',
            'theme' => 'الثيم',
            'light_theme' => 'فاتح',
            'dark_theme' => 'داكن',
            'notifications' => 'الإشعارات',
            'email_notifications' => 'إشعارات البريد الإلكتروني',
            'push_notifications' => 'إشعارات فورية',
            'promo_notifications' => 'العروض الترويجية',
            'preferences' => 'التفضيلات',
            'currency' => 'العملة',
            'timezone' => 'المنطقة الزمنية',
            'save_settings' => 'حفظ الإعدادات',
            'back' => 'رجوع',
            'current_language' => 'اللغة الحالية',
            
            // Messages
            'settings_saved' => 'تم حفظ الإعدادات بنجاح!',
            'error_saving' => 'خطأ في حفظ الإعدادات',
            'language_changed' => 'تم تغيير اللغة',
            
            // Produits
            'add_to_cart' => 'أضف إلى السلة',
            'price' => 'السعر',
            'quantity' => 'الكمية',
            'total' => 'المجموع',
            'empty_cart' => 'سلتك فارغة',
            'checkout' => 'إتمام الطلب',
            
            // Autres
            'welcome' => 'مرحباً',
            'search' => 'بحث',
            'categories' => 'الفئات',
            'contact' => 'اتصل بنا',
            'about' => 'من نحن'
        ],
        'es' => [
            // Navigation
            'home' => 'Inicio',
            'shop' => 'Tienda',
            'cart' => 'Carrito',
            'profile' => 'Perfil',
            'dashboard' => 'Panel',
            'settings' => 'Ajustes',
            'logout' => 'Cerrar sesión',
            'login' => 'Iniciar sesión',
            
            // Paramètres
            'settings_title' => 'Ajustes',
            'language' => 'Idioma',
            'choose_language' => 'Elige tu idioma',
            'appearance' => 'Apariencia',
            'theme' => 'Tema',
            'light_theme' => 'Claro',
            'dark_theme' => 'Oscuro',
            'notifications' => 'Notificaciones',
            'email_notifications' => 'Notificaciones por email',
            'push_notifications' => 'Notificaciones push',
            'promo_notifications' => 'Ofertas promocionales',
            'preferences' => 'Preferencias',
            'currency' => 'Moneda',
            'timezone' => 'Zona horaria',
            'save_settings' => 'Guardar ajustes',
            'back' => 'Volver',
            'current_language' => 'Idioma actual',
            
            // Messages
            'settings_saved' => '¡Ajustes guardados con éxito!',
            'error_saving' => 'Error al guardar ajustes',
            'language_changed' => 'Idioma cambiado',
            
            // Produits
            'add_to_cart' => 'Añadir al carrito',
            'price' => 'Precio',
            'quantity' => 'Cantidad',
            'total' => 'Total',
            'empty_cart' => 'Tu carrito está vacío',
            'checkout' => 'Finalizar compra',
            
            // Autres
            'welcome' => 'Bienvenido',
            'search' => 'Buscar',
            'categories' => 'Categorías',
            'contact' => 'Contacto',
            'about' => 'Acerca de'
        ],
        'de' => [
            // Navigation
            'home' => 'Startseite',
            'shop' => 'Shop',
            'cart' => 'Warenkorb',
            'profile' => 'Profil',
            'dashboard' => 'Dashboard',
            'settings' => 'Einstellungen',
            'logout' => 'Abmelden',
            'login' => 'Anmelden',
            
            // Paramètres
            'settings_title' => 'Einstellungen',
            'language' => 'Sprache',
            'choose_language' => 'Wähle deine Sprache',
            'appearance' => 'Aussehen',
            'theme' => 'Thema',
            'light_theme' => 'Hell',
            'dark_theme' => 'Dunkel',
            'notifications' => 'Benachrichtigungen',
            'email_notifications' => 'E-Mail-Benachrichtigungen',
            'push_notifications' => 'Push-Benachrichtigungen',
            'promo_notifications' => 'Werbeangebote',
            'preferences' => 'Präferenzen',
            'currency' => 'Währung',
            'timezone' => 'Zeitzone',
            'save_settings' => 'Einstellungen speichern',
            'back' => 'Zurück',
            'current_language' => 'Aktuelle Sprache',
            
            // Messages
            'settings_saved' => 'Einstellungen erfolgreich gespeichert!',
            'error_saving' => 'Fehler beim Speichern der Einstellungen',
            'language_changed' => 'Sprache geändert'
        ],
        'it' => [
            // Navigation
            'home' => 'Home',
            'shop' => 'Negozio',
            'cart' => 'Carrello',
            'profile' => 'Profilo',
            'dashboard' => 'Pannello',
            'settings' => 'Impostazioni',
            'logout' => 'Esci',
            'login' => 'Accedi',
            
            // Paramètres
            'settings_title' => 'Impostazioni',
            'language' => 'Lingua',
            'choose_language' => 'Scegli la tua lingua',
            'appearance' => 'Aspetto',
            'theme' => 'Tema',
            'light_theme' => 'Chiaro',
            'dark_theme' => 'Scuro',
            'notifications' => 'Notifiche',
            'email_notifications' => 'Notifiche email',
            'push_notifications' => 'Notifiche push',
            'promo_notifications' => 'Offerte promozionali',
            'preferences' => 'Preferenze',
            'currency' => 'Valuta',
            'timezone' => 'Fuso orario',
            'save_settings' => 'Salva impostazioni',
            'back' => 'Indietro',
            'current_language' => 'Lingua attuale',
            
            // Messages
            'settings_saved' => 'Impostazioni salvate con successo!',
            'error_saving' => 'Errore durante il salvataggio',
            'language_changed' => 'Lingua cambiata'
        ]
    ];
    
    return $translations[$lang] ?? $translations['fr'];
}

// Obtenir le symbole de la devise
function getCurrencySymbol($currency = null) {
    global $available_currencies;
    if ($currency === null) {
        $currency = $_SESSION['settings']['currency'] ?? 'MAD';
    }
    return $available_currencies[$currency]['symbol'] ?? 'DH';
}

// Obtenir le nom de la devise
function getCurrencyName($currency = null) {
    global $available_currencies;
    if ($currency === null) {
        $currency = $_SESSION['settings']['currency'] ?? 'MAD';
    }
    return $available_currencies[$currency]['name'] ?? 'Dirham marocain';
}

// Obtenir la liste des langues
function getAvailableLanguages() {
    global $available_languages;
    return $available_languages;
}

// Obtenir la liste des devises
function getAvailableCurrencies() {
    global $available_currencies;
    return $available_currencies;
}

// Formater le prix
function formatPrice($price, $currency = null) {
    if ($currency === null) {
        $currency = $_SESSION['settings']['currency'] ?? 'MAD';
    }
    $symbol = getCurrencySymbol($currency);
    return number_format($price, 2, ',', ' ') . ' ' . $symbol;
}

// Appliquer les paramètres
applySettings();
?>