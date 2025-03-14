<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Attendance and Performance Monitoring System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Additional styles for the index page */
        .hero {
            text-align: center;
            padding: 100px 20px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),url('bk.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for better text visibility */
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            position: relative;
            z-index: 1;
        }
        .cta-buttons a {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        .cta-buttons a:hover {
            background-color: #218838;
        }
        .features {
            padding: 50px 20px;
            text-align: center;
        }
        .features h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .features .feature-list {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        .features .feature {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            width: 250px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .features .feature h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .features .feature p {
            font-size: 1rem;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <h1 style="color:rgb(70, 227, 107);">Welcome to the Attendance and Performance Monitoring System</h1>
        <p>Manage attendance and performance records efficiently with our system.</p>
        <div class="cta-buttons">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2>Key Features</h2>
        <div class="feature-list">
            <div class="feature">
                <h3>Attendance Tracking</h3>
                <p>Easily record and manage student attendance for each class.</p>
            </div>
            <div class="feature">
                <h3>Performance Monitoring</h3>
                <p>Track student performance with marks and remarks.</p>
            </div>
            <div class="feature">
                <h3>Role-Based Access</h3>
                <p>Admins, teachers, and students have different access levels.</p>
            </div>
            <div class="feature">
                <h3>Generate Reports</h3>
                <p>Export attendance and performance data in Excel or PDF format.</p>
            </div>
        </div>
    </div>
    <footer>
    &copy; 2025 - All right reserved - CAS&trade;
    </footer>
</body>
</html>