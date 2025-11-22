<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="php.css">
        <title>BrainBoost | Quiz</title>
        <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">

        <style>
            body {
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            form {
                width: 350px;
                background: white;
                padding: 25px;
                border-radius: 15px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            }

            input[type=text], input[type=password] {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                border: 1px solid #ccc;
                border-radius: 8px;
                box-sizing: border-box;
            }

            button {
                background-color: rgb(25, 29, 153);
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                width: 100%;
                font-size: 16px;
            }

            button:hover {
                opacity: 0.9;
            }

            .container {
                padding: 10px 0;
            }

            span.psw {
                float: right;
                margin-top: 10px;
            }

            @media screen and (max-width: 300px) {
                form {
                    width: 90%;
                }

                span.psw {
                    float: none;
                    display: block;
                    text-align: center;
                }
            }
        </style>
    </head>

    <body>
        <form action="action_page.php" method="post">

            <div class="container">
                <label for="uname"><b>Inloggen</b></label>
                <input type="text" placeholder="Uw gebruikersnaam" name="uname" required>

                <label for="psw"></label>
                <input type="password" placeholder="Uw wachtwoord" name="psw" required>

                <button type="submit"><a href= "index.php"> Login </a></button>

                <label>
                    <input type="checkbox" checked="checked" name="remember"> Onthoud mij
                </label>
            </div>
        </form>
    </body>
</html>