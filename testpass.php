<?php
$hash = '$2y$10$FRWwdRRJUlOyXSfi42vOZOUeOOmT5kU/qUb5cys52pUBg0Dq0Z3Ce';
$plain = '7980312297'; // test a guess here

if (password_verify($plain, $hash)) {
    echo "✅ Match: $plain is the correct password";
} else {
    echo "❌ Not a match";
}
?>