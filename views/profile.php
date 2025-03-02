<?php
    // profile.php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    $baseDir = __DIR__ . '/'; // Base directory of the project
    include_once $baseDir . '../models/Tweet.php'; // Include Tweet class
    include_once $baseDir . '../models/User.php'; // Include User class
    include $baseDir . '../views/header.php'; // Include header file
    
    $loggedInUserId = $_SESSION['user_id']; // Get the logged-in user's ID
    $profileUser = User::getById($loggedInUserId); // Fetch user data
    $tweets = Tweet::getByUserId($loggedInUserId); // Fetch user's tweets
    $followedTweets = Tweet::getByFollowedUsers($loggedInUserId); // Fetch tweets of followed users
    $followers = User::getFollowers($loggedInUserId); // Fetch followers
    $following = User::getFollowing($loggedInUserId); // Fetch following
?>

<main>
    <div class="tweets-layout">
        <!-- Form and Tweets Container -->
        <div class="tweets-and-form">
            <!-- Form Section -->
            <form class="tweet-form" method="POST" action="profile.php" enctype="multipart/form-data">
                <h2>Compose a Tweet</h2>
                <label>
                    <textarea name="content" placeholder="What's happening?" required></textarea>
                </label>
                <label>
                    <input type="file" name="image" accept="image/*">
                </label>
                <button type="submit">Tweet</button>
            </form>

            <!-- Tweets Section -->
            <section class="tweets">
                <h1>Your Tweets, <?php echo htmlspecialchars($profileUser['username']); ?>!</h1>
                <ul class="tweets-list">
                    <?php
                        if (!empty($tweets)) { // Check if there are any tweets
                            foreach ($tweets as $tweet) {
                                echo "<li class='tweet-item'>
                        <strong>{$tweet['username']}</strong> - {$tweet['content']}
                        <br>
                        <small>Likes: {$tweet['like_count']}</small>";
                                if ($tweet['image_path']) {
                                    echo "<br><img src='{$tweet['image_path']}' alt='Tweet image' class='tweet-image'>";
                                }
                                echo "</li>";
                            }
                        } else {
                            echo "<p>You haven't tweeted yet!</p>";
                        }
                    ?>
                </ul>
            </section>

            <!-- Followed Users' Tweets Section -->
            <section class="tweets">
                <h1>Tweets from those you follow:</h1>
                <ul class="tweets-list">
                    <?php
                        if (!empty($followedTweets)) { // Check if there are any tweets from those the user follows
                            foreach ($followedTweets as $tweet) {
                                echo "<li class='tweet-item'>
                        <strong>{$tweet['username']}</strong> - {$tweet['content']}
                        <br>
                        <small>Likes: {$tweet['like_count']}</small>";
                                if ($tweet['image_path']) {
                                    echo "<br><img src='{$tweet['image_path']}' alt='Tweet image' class='tweet-image'>";
                                }
                                echo "</li>";
                            }
                        } else {
                            echo "<p>People you follow haven't tweeted yet!</p>";
                        }
                    ?>
                </ul>
            </section>
        </div>

        <!-- Followers and Following Info -->
        <aside class="users">
            <h2>Followers & Following</h2>
            <div class="followers-following-section">
                <!-- Followers Section -->
                <div class="followers-section">
                    <h3>Followers</h3>
                    <ul class="users-list">
                        <?php
                            if (!empty($followers)) {
                                foreach ($followers as $follower) {
                                    echo "<li>{$follower['username']}</li>";
                                }
                            } else {
                                echo "<p>No followers yet.</p>";
                            }
                        ?>
                    </ul>
                </div>

                <!-- Following Section -->
                <div class="following-section">
                    <h3>Following</h3>
                    <ul class="users-list">
                        <?php
                            if (!empty($following)) {
                                foreach ($following as $followed) {
                                    echo "<li>{$followed['username']}</li>";
                                }
                            } else {
                                echo "<p>You're not following anyone yet.</p>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</main>