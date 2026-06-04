<?php

/*
| Central app routes (localhost / CENTRAL_DOMAIN hosts only).
| Loaded from routes/web.php inside Route::domain(...) groups.
|
| - routes/central/shared.php  — landing, auth, explore (portal.port:any)
| - routes/central/admin.php   — platform admin / CA (portal.port:admin)
| - routes/central/guest.php   — municipality guest + owner (portal.port:public)
*/

require __DIR__.'/central/shared.php';
require __DIR__.'/central/admin.php';
require __DIR__.'/central/guest.php';
