<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'functions.php';

if(isset($_POST["login"])){

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $result = mysqli_query($conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND role='admin'"
    );

    if(!$result){
        die(mysqli_error($conn));
    }

    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_assoc($result);
if(password_verify($password, $row["password"])){

    $_SESSION["login"] = true;
    $_SESSION["id"] = $row["id"];
    $_SESSION["nama"] = $row["nama"];
    $_SESSION["username"] = $row["username"];
    $_SESSION["email"] = $row["email"];
    $_SESSION["role"] = $row["role"];

    header("Location: admin/index.php");
    exit;
}

    } else {
        $error = "Akun admin tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }

        form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
            font-size: 14px;
            text-align: center;
        }

        @media (max-width: 480px) {
            form {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<form method="POST">
    <h1>Login Admin</h1>

    <?php if(isset($error)): ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <label>Email</label>
    <input type="email"
           name="email"
           value="admin@gmail.com"
           required 
           placeholder="Masukkan email admin">

    <label style="margin-top: 20px;">Password</label>
    <input type="password"
           name="password"
           value="12345"
           required
           placeholder="Masukkan password">

    <button type="submit" name="login">
        Login
    </button>
</form>

</body>
</html>