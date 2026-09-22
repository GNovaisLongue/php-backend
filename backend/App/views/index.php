<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API</title>
</head>
<body>
    <div style="margin: 100px; font-family: sans-serif">
        <p>API is working!</p>
        <h3>Products</h3>
        <ul>
            <li><a href="./products">GET /products</a> — list all</li>
            <li>GET /product/{sku} — single product</li>
            <li>POST /product — create (JSON body)</li>
            <li>DELETE /product/{sku} — delete one</li>
            <li>DELETE /products — mass delete (JSON body: {"skus": [...]})</li>
        </ul>
        <h3>Users</h3>
        <ul>
            <li><a href="./users">GET /users</a> — list all</li>
            <li>GET /user/{id} — single user</li>
            <li>POST /user — create (JSON body)</li>
            <li>DELETE /user/{id} — delete one</li>
        </ul>
        <p>Use <b>POSTMAN</b> or similar for POST and DELETE methods.</p>
    </div>
</body>
</html>
