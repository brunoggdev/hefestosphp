<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/teste1" method="post">
        <?=metodoHttp('delete')?>
        <input type="text" name="usuario">
        <input type="password" name="senha">
        <button type="submit">log in</button>
        
    </form>

</body>
</html>