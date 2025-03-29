<?php
session_start();

// Redirect if not logged in as farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: login.php");
    exit();
}

// Fetch farmer's daily weights
$farmer_id = $_SESSION['farmer_id'];
$weights = $conn->query("SELECT * FROM farmer_weights WHERE farmer_id = $farmer_id ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - Keginga Tea Farmers</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Side Menu -->
        <aside class="side-menu">
            <div class="logo">
                <img src="images/logo.png" alt="Keginga Tea Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="farmer_dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Farmer Dashboard</h1>
            </header>

            <!-- Daily Weights Table -->
            <section class="table-container">
                <h2>Daily Weights</h2>
                <?php if (count($weights) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Kilograms</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($weights as $weight): ?>
                                <tr>
                                    <td><?php echo $weight['date']; ?></td>
                                    <td><?php echo $weight['kilograms']; ?> kg</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No records found.</p>
                <?php endif; ?>
            </section>

            <!-- Monthly Weight Trends Chart -->
            <section class="chart-container">
                <h2>Monthly Weight Trends</h2>
                <canvas id="weightChart"></canvas>
            </section>
        </main>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fetch data for the chart
        const weights = <?php echo json_encode($weights); ?>;

        // Prepare data for Chart.js
        const labels = weights.map(weight => weight.date);
        const data = weights.map(weight => weight.kilograms);

        // Render the chart
        const ctx = document.getElementById('weightChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kilograms Supplied',
                    data: data,
                    borderColor: '#4CAF50',
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>