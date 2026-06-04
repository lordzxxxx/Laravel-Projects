<?php

/*
| Tenant host routes (subdomain / custom domain).
| Loaded from routes/web.php inside the tenant middleware group.
|
| - routes/tenant/public.php  — landing, browse (no auth)
| - routes/tenant/guest.php   — client bookings, messages, profile
| - routes/tenant/owner.php   — owner / tenant manager portal
| - routes/tenant/shared.php  — notifications, secure media
| - routes/auth.php           — tenant auth (login, register, verify)
*/

require __DIR__.'/tenant/public.php';
require __DIR__.'/tenant/guest.php';
require __DIR__.'/tenant/owner.php';
require __DIR__.'/tenant/shared.php';
require __DIR__.'/auth.php';
