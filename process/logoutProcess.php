<?php

include_once __DIR__ . '/../config/session.php';

end_session();

header("Location: /dpz-eims/auth/login.php");
exit;
