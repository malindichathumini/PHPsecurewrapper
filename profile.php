<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #6EE7B7, #3B82F6);
            font-family: 'Poppins', sans-serif;
        }
        /* Add this CSS to set the background image */
        .bg-image {
            background-image: url('assets/7.jpg'); /* Update the path as needed */
            background-size: cover; /* Ensure the image covers the entire area */
            background-position: center; /* Center the image */
            position: absolute; /* Position it absolutely */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1; /* Send the background behind the form */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-image"></div> <!-- Background image div -->
    <div class="bg-black backdrop-blur-sm p-8 rounded-xl shadow-2xl w-full max-w-md space-y-6">
       <h1 class="text-3xl font-bold text-center text-white mb-6">User Profile</h1> <!-- Changed to text-white -->

        <?php
        // Check if the session is not already started before starting one
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require './src/db/connection.php';

        // Get database connection
        $pdo = getDBConnection();

        // Initialize variables
        $message = '';
        $error = '';

        // Fetch user data
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'User not found.';
            }
        } else {
            $error = 'You are not logged in.';
        }

        // Handle form submission for updating the profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_username = trim($_POST['username']);
            $new_email = trim($_POST['email']);

            if (empty($new_username) || empty($new_email)) {
                $error = 'Username and email cannot be empty.';
            } else {
                // Update user data
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$new_username, $new_email, $user_id]);

                $_SESSION['username'] = $new_username;
                $message = 'Profile updated successfully!';
            }
        }

        // Handle account deletion
        if (isset($_POST['deleteAccount'])) {
            try {
                // Begin a transaction
                $pdo->beginTransaction();

                // Delete from user_logs table
                $stmt = $pdo->prepare('DELETE FROM user_logs WHERE user_id = ?');
                $stmt->execute([$user_id]);

                // Delete from failed_logins table
                $stmt = $pdo->prepare('DELETE FROM failed_logins WHERE user_id = ?');
                $stmt->execute([$user_id]);

                // Delete the user from the users table
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                $stmt->execute([$user_id]);

                // Commit the transaction
                $pdo->commit();

                // Destroy session and redirect
                session_destroy();
                header('Location: ./login.php');
                exit();
            } catch (Exception $e) {
                // Roll back the transaction in case of error
                $pdo->rollBack();
                $error = 'Failed to delete the account: ' . $e->getMessage();
            }
        }
        ?>

        <!-- Profile Update Form -->
        <form action="./profile.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                <input type="text" id="username" name="username" required
                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                       class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 placeholder-gray-400">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                       class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition duration-200 placeholder-gray-400">
            </div>

            <!-- Horizontal Buttons for Update Profile and Delete Account -->
            <div class="flex justify-between space-x-4">
                <button type="submit" class="w-1/2 py-3 bg-white hover:bg-gray-200 text-black font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                  Update Profile
                </button>

                <!-- Account Deletion Button -->
                <input type="hidden" name="deleteAccount" value="1">
                <button type="submit" class="w-1/2 py-3 bg-white hover:bg-gray-200 text-black font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-300">
                  Delete Account
                </button>
            </div>

            <!-- Back to Dashboard inside the form box -->
            <div class="mt-6 text-center">
                <a href="./my_dashboard.php" class="text-red-500 hover:text-red-300 transition duration-200">Back to Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
