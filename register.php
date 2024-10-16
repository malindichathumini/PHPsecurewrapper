<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #6EE7B7, #3B82F6);
            font-family: 'Poppins', sans-serif;
        }
        /* Add this CSS to set the background image */
        .bg-image {
            background-image: url('assets/10.jpg'); /* Update the path as needed */
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
    <form action="./src/auth/register.php" method="POST" class="bg-black backdrop-blur-sm p-8 rounded-xl shadow-2xl w-full max-w-md space-y-6">
        <h1 class="text-3xl font-bold text-center text-white mb-6">Register Here,</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-500 text-white p-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
            <input type="text" id="username" name="username" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your username">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <input type="email" id="email" name="email" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your email">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" id="password" name="password" required class="mt-2 w-full px-4 py-3 bg-gray-700 text-white border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400" placeholder="Enter your password">
        </div>

        <div>
            <input type="submit" value="Register" class="w-full py-3 bg-white text-black font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition duration-300">
        </div>

        <p class="text-sm text-center text-yellow-500 mt-6">Already have an account? <a href="./login.php" class="text-red-500 hover:text-red-300 transition duration-200">Login here</a></p>
    </form>
</body>
</html>
