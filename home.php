<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lingo Swap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 30px;
            font-size: 18px;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-create {
            background-color: #28a745;
        }

        .btn-create:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Lingo Swap</h1>
    <p>This is where we are going to explain what Lingo Swap is and why you should join</p>
       
    <div class="buttons">
        <a href="create_user.php"><button class="btn-create">Create Account</button></a>
        <a href="login.php"><button>Login</button></a>
    </div>
</div>

</body>
</html>
