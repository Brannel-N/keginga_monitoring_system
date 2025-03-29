<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/JEAN/include/header.css">
</head>
<body>
<div>
        
        
        <nav>
        <div class="logo">
            <img src="/JEAN/imgz/logo.png" alt="Keginga Tea Logo">
        </div>
        <ul>
            <li><a href="index.php" class="<?php echo ($pageTitle == 'Home') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="admin.php" class="<?php echo ($pageTitle == 'Dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="farmers.php" class="<?php echo ($pageTitle == 'Farmers') ? 'active' : ''; ?>">Farmers</a></li>
            <li><a href="suppliers.php" class="<?php echo ($pageTitle == 'Suppliers') ? 'active' : ''; ?>">Suppliers</a></li>
            <li><a href="register.php" class="<?php echo ($pageTitle == 'Register') ? 'active' : ''; ?>">Register</a></li>
            <li><a href="login.php" class="<?php echo ($pageTitle == 'Login') ? 'active' : ''; ?>">Login</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>

            <?php endif; ?>
        </ul>
        </nav>
    
</div>
</body>
</html>
