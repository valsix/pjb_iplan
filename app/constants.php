<?php

define('ROLE_ID_SPV_TOR', 25);  // Supervisor Unit (TOR)
define('ROLE_ID_MGR_TOR', 26);  // Manager Unit (TOR)
define('ROLE_ID_GM', 4);    // GM
define('ROLE_ID_STAFF', 9); // STek
define('ROLE_ID_KABID', 10); // Kabid Teknologi
define('ROLE_ID_ADM', 1);  // Administrator
define('ROLE_ID_SPV_UNIT', 2);  // Supervisor Unit
define('ROLE_ID_SPV_UNIT_DMR', 12);  // Supervisor Unit DMR
define('ROLE_ID_MANAGER_UNIT_DMR', 11); // Manager Unit DMR
define('ROLE_ID_MANAGER_RISK', 27); // Manager Risk
define('ROLE_ID_KADIV_RISK', 28); // Kadiv Risk
define('ROLE_ID_STAFF_ANGGARAN', 5); // Staff Anggaran
define('ROLE_ID_ADMIN', 1); // Admin

// DMR review status
define('DMR_STATUS_APPROVED', 1);
define('DMR_STATUS_REVISED', 2);
define('DMR_STATUS_REJECTED', 3);
define('DMR_STATUS_QUEUE', 4);

// Max Phase KP/Non KP
define('MAX_PHASE_NON_KP', 4);
define('MAX_PHASE_KP', 6);

// Minimum Anggaran
define('MINIMUM_ANGGARAN', '3000000');

// ROLE_CHAIN['current_role_id'] = 'next_role_id'
define('ROLE_REVIEW_CHAIN', [
    ROLE_ID_SPV_TOR => ROLE_ID_MGR_TOR,
    ROLE_ID_MGR_TOR => ROLE_ID_GM,
    ROLE_ID_GM      => ROLE_ID_STAFF,
    ROLE_ID_STAFF   => ROLE_ID_KABID,
]);
// Asosiasi role_id dengan review phase (dengan syarat nilai is_submitted == 1)
define('ROLE_REVIEW_PHASE', [
    1 => ROLE_ID_MGR_TOR,
    2 => ROLE_ID_GM,
    3 => ROLE_ID_STAFF,
    4 => ROLE_ID_KABID,
]);

// Tor review status
define('TOR_STATUS_APPROVED', 1);
define('TOR_STATUS_REVISED', 2);
define('TOR_STATUS_REJECTED', 3);
define('TOR_STATUS_QUEUE', 4);
