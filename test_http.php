<?php
$headers = get_headers('http://127.0.0.1:8000/rental/1/guest-booking/61c65057-ec4e-43ac-afc1-9d9d23ec1598', 1);
print_r($headers);
