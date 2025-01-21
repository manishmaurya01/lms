<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal overflow */
        }
        
        h2 {
            text-align: center;
            color: #f1f1f1;
            margin-bottom: 30px;
        }
        
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            box-sizing: border-box; /* Ensure padding does not affect width */
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #bbb;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            background-color: #333;
            border: 1px solid #444;
            color: #e0e0e0;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box; /* Prevent overflow caused by padding */
        }

        input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        /* Button Styles */
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            box-sizing: border-box; /* Prevent overflow caused by padding */
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Error Message */
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 15px;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .container {
                padding: 15px;
                width: 100%;
                box-sizing: border-box; /* Ensure padding does not affect width */
            }

            input {
                font-size: 16px;
            }

            .btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Login</h2>
    <form method="POST" action="loginprocess.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>
</html>
