<?php
if (!empty($_GET["id"])) {
    header("Location: /browse/detail/{$_GET['id']}", true, 301);
} else {
    header("Location: /", true, 301);
}