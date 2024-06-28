<?=comp('padroes_hefestos')?>


<?php

if (sessao()->tem('toast')) {
    $toast = sessao('toast');
    echo "<script type=module> novo_toast('$toast[texto]', '$toast[cor]') </script>";
}

?>

</body>
</html>