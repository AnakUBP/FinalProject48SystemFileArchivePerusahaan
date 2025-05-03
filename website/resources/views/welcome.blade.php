    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/app.css"/>
    </head>
    <body>
    <div class="container">
        <div class="left-side">
        <!-- Tempat Logo -->
        <div class="logo">
            <img src="../img/logo.svg" alt="Logo" class="logo-img" />
        </div>
        <h2 class="brand-name">Eter</h2>
        </div>

        <div class="right-side">
        <div class="login-card">
            <h2 class="login-title">Login</h2>
            <form id="login-form">
            <label>Email</label>
            <input type="email" required />

            <label>Password</label>
            <input type="password" required />

            <div class="forgot">
                <a href="#">Forget Password?</a>
            </div>

            <button type="submit">Submit</button>
            </form>
        </div>
        </div>
    </div>

    <script src="../js/app.js"></script>
    </body>
    </html>
