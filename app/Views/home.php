<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HefestosPHP - Home</title>
    <link rel="stylesheet" href="<?=url_base('hefestos.css')?>">
</head>
<body>
    <div>
        <h1></h1>
    </div>
    <script>
        const msg = 'HefestosPHP - Hello, World!'
        const h1 = document.getElementsByTagName('h1')[0]
    
        function digitar(i){

            h1.innerHTML += msg.charAt(i)

            if(i < msg.length){
                setTimeout(() => {digitar(i+1)}, 100)
            }
        }

        digitar(0)
    </script>
</body>
</html>
