<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inventory Barang Sembako</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #00c6ff, #f7797d);
        }
        .home-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .home-container h1 {
            font-size: 3rem;
            color: #000000;
            margin-bottom: 1.5rem;
        }
        .home-container .btn {
            padding: 12px 24px;
            margin: 0 10px;
            font-size: 1.1rem;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            background-color: #00bbbb;
        }
        .home-container .btn:hover {
            background-color: white;
            color: #00bbbb;
            border: 1px solid #00bbbb;
        }
    </style>
</head>
<body>
    <div class="home-container">
        <h1>Inventory Barang Sembako</h1>
        <div>
            <a href="login.php" class="btn">Sign In</a>
            <a href="register.php" class="btn">Sign Up</a>
        </div>
    </div>
</body>
</html>
