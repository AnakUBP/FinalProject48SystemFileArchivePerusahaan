    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/apprp.css" />
    </head>
    <body>
    <div class="container">
        <div class="card">
        <span class="back" onclick="goBack()">&#8592;</span>
        <h2>Reset Password</h2>
        <p>Please enter your new password and confirm password</p>
        <form onsubmit="return handleSubmit(event)">
            <label>New Password</label>
            <input type="password" id="newPassword" required />
            
            <label>Confirm Password</label>
            <input type="password" id="confirmPassword" required />

            <div class="forgot">    
            <a href="#">Forget Password?</a>
            </div>

            <button type="submit">Submit</button>
        </form>
        </div>
    </div>

    <script src="../js/apprp.js"></script>
    </body>
    </html>
