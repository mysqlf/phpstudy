<?php
$passwordHash = password_hash('password', PASSWORD_DEFAULT);

if (password_verify('password', $passwordHash)) {
    var_dump(password_verify('password', $passwordHash));
    echo $passwordHash;
} else {
    echo '123';
}
