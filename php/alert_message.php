<?php
if (isset($_SESSION['message'])) {
    echo "<script>
        window.onload = function() {
            alert('" . addslashes($_SESSION['message']) . "');
        };
    </script>";
    unset($_SESSION['message']);
}