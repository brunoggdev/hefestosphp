<?=comp('padroes_hefestos')?>


<?php if(sessao()->tem('toast')): ?>
<script type="module" >
    toast('<?= sessao('toast.texto') ?>', '<?= sessao('toast.cor') ?>')
</script>
<?php endif; ?>

</body>
</html>