<?php
// Hapus session admin, lalu kembali ke halaman login.
session_start();
session_destroy();
header("Location: login.html");
exit();
