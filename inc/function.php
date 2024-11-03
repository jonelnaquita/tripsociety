<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$_SESSION['message_timestamp'] = time();
include 'config.php';


if (isset($_POST['admin_login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the query to fetch the user by email
    $query = "SELECT * FROM tbl_account WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify if user exists and if the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Successful login, set session variables
        $_SESSION['role'] = $user['role'];
        $_SESSION['admin'] = $user['id'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];

        // Check user role and redirect accordingly
        if ($user['role'] === 'Admin') {
            $_SESSION['message'] = 'Welcome Administrator!';
            $_SESSION['response'] = 'Success';
            header("location:../admin/");
            exit();
        } else {
            $_SESSION['message'] = 'Account does not have admin privileges!';
            $_SESSION['response'] = 'Error';
            header("location:../index.php");
            exit();
        }
    } else {
        // Invalid email or password
        $_SESSION['message'] = 'Invalid email or password.';
        $_SESSION['response'] = 'Error';
        header("location:../index.php");
        exit();
    }
}


if (isset($_POST['user_login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to get user data based on the email
    $query = "SELECT * FROM tbl_user WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_verified'] == 0) {
            $_SESSION['message'] = 'Your account is not yet verified. Please check your email for verification link.';
            $_SESSION['response'] = 'Error';
            header("Location: ../user/login.php");
            exit();
        }
        // Password is correct, store user information in the session
        $_SESSION['status'] = $user['status'];
        $_SESSION['user'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_img'] = $user['profile_img'];
        $_SESSION['cover_img'] = $user['cover_img'];
        $_SESSION['travel_preferences'] = $user['travel_preferences'];

        $_SESSION['message'] = 'Welcome ' . $_SESSION['name'] . '!';
        $_SESSION['response'] = 'Success';

        header("Location: ../user/home.php");
        exit();
    } else {
        // Invalid email or password
        $_SESSION['message'] = 'Invalid email or password.';
        $_SESSION['response'] = 'Error';
        header("Location: ../user/login.php");
        exit();
    }
}



/**if (isset($_POST['verify_email'])) {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_account WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {


        $_SESSION['verified_email'] = $email;
        $random_string = array_map(fn() => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10), range(1, 20));
        foreach ($random_string as $string) {
            $verification_code = $string;
        }

        $stmt = $pdo->prepare("UPDATE tbl_account SET verification_code = :verification_code WHERE email = :email");
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        // Email Notification
        require_once(__DIR__ . '/../vendor/autoload.php');
        $config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        $config->setApiKey('partner-key', 'fYZrVRIdQGSFvHxA');
        $apiInstance = new Brevo\Client\Api\TransactionalEmailsApi(
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $verificationLink = "https://tripsociety.online/verify_email.php?id=$verification_code";
            $emailSubject = 'Email Verification Trip Society';
            $emailContent = '<html><body><p>Please verify your email address, <a href="' . $verificationLink . '">Confirmation Link</a></p></body></html>';

            // Send email
            $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
                'subject' => $emailSubject,
                'sender' => ['name' => 'Trip Society', 'email' => 'tripsociety@gmail.com'],
                'to' => [['name' => 'User', 'email' => $email]],
                'htmlContent' => $emailContent
            ]);

            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            if ($result) {
                header("Location: ../verify_email.php");
                exit;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }



    } else {
        $_SESSION['message'] = 'Email Address does not exists!';
        $_SESSION['response'] = 'Error';
        header("Location: ../auth.php");
        exit;
    }
}
*/


if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = 'Password not matched!';
        $_SESSION['response'] = 'Error';
        header("Location: ../reset_password.php");
        exit;
    }

    $verified_email = $_SESSION['verified_email'];
    $stmt = $pdo->prepare("UPDATE tbl_account SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $new_password);
    $stmt->bindParam(':email', $verified_email);
    $stmt->execute();
    unset($_SESSION['verified_email']);
    $_SESSION['message'] = 'Password changed successfuly!';
    $_SESSION['response'] = 'Success';
    header("Location: ../password_checked.php");
    exit;
}


if (isset($_POST['add_location'])) {
    $locationName = $_POST['location_name'];
    $city = $_POST['city'];
    $description = $_POST['description'];
    // $virtualTourLink = $_POST['virtual_tour_link'];


    // Concatenate categories
    $categories = isset($_POST['category']) ? $_POST['category'] : [];
    $categoriesString = implode(', ', $categories);

    $location = $_POST['location1'];
    $locations = isset($_POST['location']) ? $_POST['location'] : [];

    $instructions = isset($_POST['instruction']) ? $_POST['instruction'] : [];

    // Combine location and instruction pairs
    $combinedPairs = [];
    for ($i = 0; $i < count($locations); $i++) {
        if (isset($locations[$i]) && isset($instructions[$i])) {
            $combinedPairs[] = $locations[$i] . '-' . $instructions[$i];
        }
    }
    $combinedString = implode(', ', $combinedPairs);


    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploadDir = '../admin/images/';
        $fileNames = [];
        $errors = [];

        // Iterate through each uploaded file
        foreach ($_FILES['images']['name'] as $key => $name) {
            // Prepare the file paths and move the uploaded file
            $tmpName = $_FILES['images']['tmp_name'][$key];
            $targetFile = $uploadDir . basename($name);

            if (move_uploaded_file($tmpName, $targetFile)) {
                $fileNames[] = $name;
            } else {
                $errors[] = 'Failed to upload ' . htmlspecialchars($name);
            }
        }
        if (count($errors) > 0) {
            $_SESSION['message'] = implode('<br>', $errors) . '<br>';
            $_SESSION['response'] = 'Error';
            header("Location: ../admin/location.php");
        }
        $images = implode(',', $fileNames);
    }

    if (isset($_FILES['tour_link']) && $_FILES['tour_link']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../admin/panorama/';
        $fileName = basename($_FILES['tour_link']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['tour_link']['tmp_name'], $targetFile)) {

            $tour_link = $fileName;
        } else {
            $_SESSION['message'] = 'Failed to upload tour link';
            $_SESSION['response'] = 'Error';
            header("Location: ../admin/location.php");
        }
    }


    try {
        // Prepare SQL statement
        $sql = "INSERT INTO tbl_location (location_name, description, category, tour_link, instruction, image, location, city) 
                    VALUES (:location_name, :description, :category, :tour_link, :how_to_get_there, :images, :location, :city)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':location_name', $locationName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $categoriesString);
        $stmt->bindParam(':tour_link', $tour_link);
        $stmt->bindParam(':how_to_get_there', $combinedString);
        $stmt->bindParam(':images', $images);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':city', $city);
        $stmt->execute();

        $_SESSION['message'] = 'Location Added Successfuly!';
        $_SESSION['response'] = 'Success';
        header("Location: ../admin/location.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}



if (isset($_POST['update_user_location'])) {
    $current_location = $_POST['current_location'];
    $user_id = $_SESSION['user'];
    $stmt = $pdo->prepare("UPDATE tbl_user SET current_location = :current_location WHERE id = :user_id");
    $stmt->execute([
        ':current_location' => $current_location,
        ':user_id' => $user_id
    ]);

}

if (isset($_POST['update_location'])) {
    $id = $_POST['id'];
    $locationName = $_POST['location_name'];
    $description = $_POST['description'];
    $tourLink = $_POST['tour_link'];
    $categories = isset($_POST['category']) ? $_POST['category'] : [];
    $categoriesString = implode(', ', $categories);

    try {
        $sql = "UPDATE tbl_location 
                SET location_name = :location_name, description = :description, category = :category, tour_link = :tour_link 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':location_name', $locationName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $categoriesString);
        $stmt->bindParam(':tour_link', $tourLink);
        $stmt->execute();
        $_SESSION['message'] = 'Location Updated Successfuly!';
        $_SESSION['response'] = 'Success';
        header("Location: ../admin/location.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


if (isset($_POST['delete_location'])) {
    $id = $_POST['id'];
    try {
        $sql = "DELETE FROM tbl_location WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['message'] = 'Location Deleted Successfuly!';
        $_SESSION['response'] = 'Success';
        header("Location: ../admin/location.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}




if (isset($_POST['add_travel_preference'])) {
    $travelPreferences = $_POST['travel_preferences'];
    $userId = $_SESSION['user'];

    if (!empty($travelPreferences) && !empty($userId)) {
        try {
            // Update travel preferences in the database
            $sql = "UPDATE tbl_user SET travel_preferences = :travel_preferences WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':travel_preferences', $travelPreferences);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            // Fetch updated user information
            $sqlFetch = "SELECT * FROM tbl_user WHERE id = :user_id";
            $stmtFetch = $pdo->prepare($sqlFetch);
            $stmtFetch->bindParam(':user_id', $userId);
            $stmtFetch->execute();
            $user = $stmtFetch->fetch(PDO::FETCH_ASSOC);

            // Save user information to the session
            if ($user) {
                $_SESSION['user'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile_img'] = $user['profile_img'];
                $_SESSION['cover_img'] = $user['cover_img'];
                $_SESSION['travel_preferences'] = $user['travel_preferences'];
            }

            // Redirect to home page after successful update
            header("Location: ../user/home.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = 'Please select your travel preferences';
        $_SESSION['response'] = 'Error';
        header("Location: ../user/travel_preference.php");
        exit();
    }
}


if (isset($_GET['view_document'])) {
    $userId = $_GET['id'];
    $result = viewDocument($pdo, $userId);
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No data found.']);
    }
}


if (isset($_POST['decline_user'])) {
    $userId = $_POST['id'];
    $result = declineUser($pdo, $userId);
    if ($result) {
        header('Location: ../admin/user.php');
    }
}

if (isset($_POST['approve_user'])) {
    $userId = $_POST['id'];
    $result = approveUser($pdo, $userId);
    if ($result) {
        header('Location: ../admin/user.php');
    }
}




if (isset($_POST['accept_invite'])) {
    // Ensure the required POST parameters are set
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $userId = $_POST['user_id'];
        $companionId = $_SESSION['user'];
        $status = 'Accepted'; // Set the new status as needed

        try {
            // Prepare the SQL statement to update the status
            $stmt = $pdo->prepare("
                UPDATE tbl_travel_companion
                SET status = :status
                WHERE companion_id = :companion_id AND user_id = :user_id
            ");

            // Bind parameters
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Update successful
                $_SESSION['message'] = 'Travel companion request accepted successfully!';
                $_SESSION['response'] = 'Success';
            } else {
                // Update failed
                $_SESSION['message'] = 'Failed to accept travel companion request.';
                $_SESSION['response'] = 'Error';
            }
        } catch (PDOException $e) {
            // Handle database errors
            $_SESSION['message'] = 'Database error: ' . $e->getMessage();
            $_SESSION['response'] = 'Error';
        }

        // Redirect to the appropriate page
        header("Location: ../user/profile.php?id=$userId"); // Adjust this URL as needed
        exit();
    } else {
        $_SESSION['message'] = 'User ID is missing.';
        $_SESSION['response'] = 'Error';
        header("Location: ../user/profile.php"); // Redirect to a relevant page
        exit();
    }
}


if (isset($_POST['add_feedback'])) {
    $user_id = $_SESSION['user'];
    $rating = isset($_POST['rate']) ? (int) $_POST['rate'] : 0;

    $app_improvement = isset($_POST['app_improvement']) ? implode(',', array_map('htmlspecialchars', $_POST['app_improvement'])) : '';

    $comment = isset($_POST['feedback']) ? htmlspecialchars($_POST['feedback']) : '';

    if ($rating < 0 || $rating > 5) {
        $rating = 0;
    }

    $sql = "INSERT INTO tbl_feedback (user_id, rate, app_improvement, feedback) VALUES (:user_id, :rating, :app_improvement, :comment)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $user_id,
        'rating' => $rating,
        'app_improvement' => $app_improvement,
        'comment' => $comment,
    ]);

    $_SESSION['message'] = 'Your feedback helps us understand your experience and guides our improvements.';
    $_SESSION['response'] = 'Success';
    header("Location: ../user/home.php");
}




if (isset($_GET['add_profile_image'])) {
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_img']['tmp_name'];
        $fileName = $_FILES['profile_img']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $id = $_SESSION['user'];
        $newFileName = uniqid() . '.' . $fileExtension;
        $destinationPath = '../admin/profile_image/' . $newFileName;

        // Move the file to the destination directory
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            // Update the database with the new file path
            $stmt = $pdo->prepare("UPDATE tbl_user SET profile_img = :fileName WHERE id = :userId");
            $stmt->bindParam(':fileName', $newFileName, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Profile image updated successfully.';
                $_SESSION['response'] = 'Success';
                header("Location: ../user/user.php");

            } else {
                $_SESSION['message'] = 'Error uploading image';
                $_SESSION['response'] = 'Error';
                header("Location: ../user/user.php");
            }
        } else {
            $_SESSION['message'] = 'Error uploading image';
            $_SESSION['response'] = 'Error';
            header("Location: ../user/user.php");
        }
    } else {
        $_SESSION['message'] = 'Error uploading image';
        $_SESSION['response'] = 'Error';
        header("Location: ../user/user.php");
    }
}





if (isset($_GET['add_cover_image'])) {
    if (isset($_FILES['cover_img']) && $_FILES['cover_img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cover_img']['tmp_name'];
        $fileName = $_FILES['cover_img']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $id = $_SESSION['user'];
        $newFileName = uniqid() . '.' . $fileExtension;
        $destinationPath = '../admin/cover_image/' . $newFileName;

        // Move the file to the destination directory
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            // Update the database with the new file path
            $stmt = $pdo->prepare("UPDATE tbl_user SET cover_img = :fileName WHERE id = :userId");
            $stmt->bindParam(':fileName', $newFileName, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Profile image updated successfully.';
                $_SESSION['response'] = 'Success';
                header("Location: ../user/user.php");

            } else {
                $_SESSION['message'] = 'Error uploading image';
                $_SESSION['response'] = 'Error';
                header("Location: ../user/user.php");
            }
        } else {
            $_SESSION['message'] = 'Error uploading image';
            $_SESSION['response'] = 'Error';
            header("Location: ../user/user.php");
        }
    } else {
        $_SESSION['message'] = 'Error uploading image';
        $_SESSION['response'] = 'Error';
        header("Location: ../user/user.php");
    }
}



if (isset($_POST['add_travel_companion'])) {
    // Check if companion_id is set
    if (isset($_POST['companion_id']) && !empty($_POST['companion_id'])) {
        $companionId = $_POST['companion_id'];
        $status = 'Requesting';

        try {
            // Check if the companion_id already exists for the current user
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_travel_companion WHERE companion_id = :companion_id AND user_id = :user_id");
            $checkStmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
            $checkStmt->bindParam(':user_id', $_SESSION['user'], PDO::PARAM_INT);
            $checkStmt->execute();

            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                // Companion already exists for the user
                $_SESSION['message'] = 'You have already requested this travel companion.';
                $_SESSION['response'] = 'Info'; // Use a different response type for info messages
            } else {
                // Insert new travel companion
                $stmt = $pdo->prepare("INSERT INTO tbl_travel_companion (companion_id, user_id, status) VALUES (:companion_id, :user_id, :status)");

                // Bind parameters
                $stmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $_SESSION['user'], PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);

                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Travel companion requested successfully!';
                    $_SESSION['response'] = 'Success';
                } else {
                    $_SESSION['message'] = 'Failed to add travel companion.';
                    $_SESSION['response'] = 'Error';
                }
            }
        } catch (PDOException $e) {
            // Handle database errors
            $_SESSION['message'] = 'Database error: ' . $e->getMessage();
            $_SESSION['response'] = 'Error';
        }
    } else {
        $_SESSION['message'] = 'Companion ID is missing.';
        $_SESSION['response'] = 'Error';
    }

    // Redirect back to the previous page or wherever appropriate
    header("Location: ../user/profile.php?id=$companionId"); // Update this to the appropriate page
    exit();
}



if (isset($_GET['get_travel_companion_request'])) {
    if (isset($_SESSION['user'])) {
        $sessionId = $_SESSION['user'];

        // Prepare the SQL statement to fetch travel companion requests
        $stmt = $pdo->prepare("
            SELECT *, tc.date_created as date
            FROM tbl_travel_companion tc
            LEFT JOIN tbl_user tu ON tu.id = tc.user_id
            WHERE companion_id = :sessionId AND tc.viewed = 0
            AND tc.status = 'Requesting'
        ");

        // Execute the query with bound parameters
        $stmt->execute(['sessionId' => $sessionId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as a JSON response
        echo json_encode($results);
        exit; // Make sure to exit to prevent any additional output
    }
}




if (isset($_POST['accept_travel_companion'])) {
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $companionId = $_SESSION['user'];
        $userId = $_POST['user_id'];
        $status = 'Accepted';

        try {
            // Check if the travel companion request exists with the status 'Requesting'
            $checkStmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM tbl_travel_companion 
                WHERE companion_id = :companion_id 
                AND user_id = :user_id 
                AND status = 'Requesting'
            ");
            $checkStmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->execute();

            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                // Update the status of the existing travel companion request
                $stmt = $pdo->prepare("
                    UPDATE tbl_travel_companion 
                    SET status = :status
                    WHERE companion_id = :companion_id 
                    AND user_id = :user_id
                ");

                // Bind parameters
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Travel companion request accepted successfully!';
                    $_SESSION['response'] = 'Success';
                } else {
                    $_SESSION['message'] = 'Failed to update travel companion request.';
                    $_SESSION['response'] = 'Error';
                }
            } else {
                // No existing request found
                $_SESSION['message'] = 'No valid travel companion request found to accept.';
                $_SESSION['response'] = 'Info'; // Use a different response type for info messages
            }
        } catch (PDOException $e) {
            // Handle database errors
            $_SESSION['message'] = 'Database error: ' . $e->getMessage();
            $_SESSION['response'] = 'Error';
        }
    } else {
        $_SESSION['message'] = 'Companion ID or User ID is missing.';
        $_SESSION['response'] = 'Error';
    }

    // Redirect back to the previous page or wherever appropriate
    header("Location: ../user/profile.php?id=$userId"); // Update this to the appropriate page
    exit();
}

if (isset($_POST['decline_travel_companion'])) {
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $companionId = $_SESSION['user'];
        $userId = $_POST['user_id'];
        $status = 'Cancelled';

        try {
            // Check if the travel companion request exists with the status 'Requesting'
            $checkStmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM tbl_travel_companion 
                WHERE companion_id = :companion_id 
                AND user_id = :user_id 
                AND status = 'Requesting'
            ");
            $checkStmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->execute();

            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                // Update the status of the existing travel companion request
                $stmt = $pdo->prepare("
                    UPDATE tbl_travel_companion 
                    SET status = :status 
                    WHERE companion_id = :companion_id 
                    AND user_id = :user_id
                ");

                // Bind parameters
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt->bindParam(':companion_id', $companionId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Travel companion request declined!';
                    $_SESSION['response'] = 'Success';
                } else {
                    $_SESSION['message'] = 'Failed to update travel companion request.';
                    $_SESSION['response'] = 'Error';
                }
            } else {
                // No existing request found
                $_SESSION['message'] = 'No valid travel companion request found to accept.';
                $_SESSION['response'] = 'Info'; // Use a different response type for info messages
            }
        } catch (PDOException $e) {
            // Handle database errors
            $_SESSION['message'] = 'Database error: ' . $e->getMessage();
            $_SESSION['response'] = 'Error';
        }
    } else {
        $_SESSION['message'] = 'Companion ID or User ID is missing.';
        $_SESSION['response'] = 'Error';
    }

    // Redirect back to the previous page or wherever appropriate
    header("Location: ../user/profile.php?id=$userId"); // Update this to the appropriate page
    exit();
}


if (isset($_GET['get_companion_count'])) {
    $userId = $_SESSION['user'];

    $stmt = $pdo->prepare("
    SELECT COUNT(*) AS count
    FROM tbl_travel_companion
    WHERE companion_id = :companionId or user_id = :user_id
    AND status = 'Requesting'
");
    $stmt->bindParam(':companionId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    echo $count;
}




if (isset($_GET['view_all_activity'])) {
    $results = getAllNotifications($pdo);
    $users = $results['users'];
    $notifications = $results['notifications'];
    $reports = $results['reports'];

    echo '<div style="height:400px; overflow:auto;">';

    // Display Users
    foreach ($users as $user) {
        echo '
        <a href="../admin/user.php?id=' . $user['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($user['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted an ID for verification</p>
                </div>
            </div>
        </a>
        ';
    }

    // Display Notifications
    foreach ($notifications as $notification) {
        echo '
        <a href="../admin/feedback.php?id=' . $notification['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($notification['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted a feedback</p>
                </div>
            </div>
        </a>
        ';
    }

    // Display Reports
    foreach ($reports as $report) {
        echo '
        <a href="../admin/reports.php?id=' . $report['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($report['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted a report</p>
                </div>
            </div>
        </a>
        ';
    }

    echo '</div>';
}


if (isset($_GET['view_unread_activity'])) {

    $results = getUnreadNotifications($pdo);
    $users = $results['users'];
    $notifications = $results['notifications'];
    $reports = $results['reports'];

    echo '<div style="height:400px; overflow:auto;">';
    foreach ($users as $user) {
        echo '
       <a href="../admin/user.php?id=' . $user['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($user['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted an ID for verification</p>
                </div>
            </div>
        </a>
        
        ';
    }

    foreach ($notifications as $notification) {
        echo '
          <a href="../admin/feedback.php?id=' . $notification['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($notification['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted a feedback</p>
                </div>
            </div>
        </a>
        ';
    }

    foreach ($reports as $report) {
        echo '
          <a href="../admin/reports.php?id=' . $report['id'] . '" class="dropdown-item">
            <div class="row ml-2">
                <div class="col-2">
                    <img src="../dist/img/avatar5.png" class="img-circle" style="width:50px;">
                </div>
                <div class="col-10">
                    <p class="font-weight-bold">' . htmlspecialchars($notification['name']) . '</p>
                    <p class="text-muted" style="font-size:13px; margin-top:-17px;">User submitted a report</p>
                </div>
            </div>
        </a>
        ';
    }

    echo '</div>';
}

if (isset($_POST['add_announcement'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $uploadDir = '../admin/announcement/';
        $uploadFile = $uploadDir . $imageName;
        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            $result = addAnnouncement($pdo, $title, $description, $imageName);
            if ($result) {
                header('Location:../admin/announcement.php');
            } else {
                echo "An error occurred while adding the announcement.";
            }
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
    }
}




if (isset($_POST['update_announcement'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $imageName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $uploadDir = '../admin/announcement/';
        $uploadFile = $uploadDir . $imageName;
        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            $result = updateAnnouncement($pdo, $id, $title, $description, $imageName);
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        $result = updateAnnouncement($pdo, $id, $title, $description);
    }
    if ($result) {
        header('Location: ../admin/announcement.php');
    } else {
        echo "An error occurred while updating the announcement.";
    }
}


if (isset($_POST['delete_announcement'])) {
    $id = $_POST['id'];
    $result = deleteAnnouncement($pdo, $id);

    if ($result) {
        header('Location: ../admin/announcement.php');
    } else {
        echo "An error occurred while deleting the announcement.";
    }
}



if (isset($_POST['add_respond'])) {
    $feedbackId = $_POST['id'];
    $adminId = $_SESSION['id'];
    $responseText = $_POST['message'];

    $result = addFeedbackResponse($pdo, $feedbackId, $adminId, $responseText);

    if ($result) {
        header('Location: ../admin/feedback.php');
    } else {
        echo "An error occurred while deleting the announcement.";
    }
}


if (isset($_POST['update_account'])) {
    $userId = $_SESSION['admin'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $result = updateAccount($pdo, $userId, $username, $email);

    if ($result) {
        header('Location: ../admin/account.php');
    } else {
        echo "An error occurred while updating account";
    }
}


if (isset($_POST['send_reset_password'])) {
    $email = $_POST['email-reset'];
    $userId = $_SESSION['admin'];
    $result = sendVerificationEmail($email, $userId);

    if ($result) {
        header('Location: ../admin/account.php');
    } else {
        echo "An error occurred while sending email";
    }
}


if (isset($_GET['view_destination'])) {


    $query = '%' . $_GET['query'] . '%';
    $stmt = $pdo->prepare("SELECT location_name, id FROM tbl_location WHERE location_name LIKE ? LIMIT 10");
    $stmt->execute([$query]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        foreach ($results as $row) {
            echo '<a class="dropdown-item" href="write_review3.php?id=' . $row['id'] . '">' . htmlspecialchars($row['location_name']) . '</a>';
        }
    } else {
        echo '<a class="dropdown-item" href="#">No results found</a>';
    }


}


if (isset($_POST['reset_password1'])) {
    $userId = $_SESSION['user'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword == $confirmPassword) {
        $result = resetPassword($pdo, $userId, $newPassword);
        if ($result) {
            header('Location: ../admin/account.php');
        } else {
            echo "An error occurred while sending email";
        }
    } else {
        setMessage('Password not match!', 'Error');
        header("location:../admin/reset_password.php?id=$userId");
    }


}



if (isset($_POST['add_post'])) {
    // Set default timezone
    date_default_timezone_set('Asia/Manila');

    // Collect and sanitize form data
    $user_id = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $post = isset($_POST['post']) ? trim($_POST['post']) : "";
    $location = isset($_POST['location']) ? trim($_POST['location']) : "";
    $images = isset($_FILES['images']) ? $_FILES['images'] : array();

    $result = addPost($pdo, $post, $location, $user_id, $images);

    if ($result) {
        header('Location: ../user/home.php');
        exit();
    } else {
        echo "An error occurred while adding the post!";
    }
}


if (isset($_GET['add_reaction'])) {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Asia/Manila
    $userId = $_SESSION['user'];
    $postId = $_POST['post_id'];
    $dateCreated = date('Y-m-d H:i:s'); // Get current date and time

    // Check if the user has already reacted
    $query = $pdo->prepare('SELECT * FROM tbl_reaction WHERE user_id = ? AND post_id = ?');
    $query->execute([$userId, $postId]);
    $reaction = $query->fetch(PDO::FETCH_ASSOC);

    if ($reaction) {
        // If reacted, remove the reaction
        $query = $pdo->prepare('DELETE FROM tbl_reaction WHERE user_id = ? AND post_id = ?');
        $query->execute([$userId, $postId]);
        $reacted = false;
    } else {
        // If not reacted, add the reaction with timestamp
        $query = $pdo->prepare('INSERT INTO tbl_reaction (user_id, post_id, date_created) VALUES (?, ?, ?)');
        $query->execute([$userId, $postId, $dateCreated]);
        $reacted = true;
    }

    header('Content-Type: application/json');
    echo json_encode(['reacted' => $reacted]);
}



if (isset($_GET['add_comment'])) {
    header('Content-Type: application/json');

    $id = $_POST['id'];
    $user_id = $_SESSION['user'];
    $message = $_POST['message'];

    // Assuming addComment function returns true on success and false on failure
    $result = addComment($pdo, $id, $user_id, $message);

    if ($result) {
        echo "success";

    } else {
        echo "error: An error occurred while inserting the comment.";
    }
}


if (isset($_GET['unread-count'])) {
    $stmtUsers = $pdo->prepare('SELECT COUNT(*) as count FROM tbl_user WHERE unread = 0 AND (id_front IS NOT NULL AND id_back IS NOT NULL AND id_front != "" AND id_back != "")');
    $stmtUsers->execute();
    $userCount = $stmtUsers->fetchColumn();

    $stmtFeedback = $pdo->prepare('SELECT COUNT(*) as count FROM tbl_feedback WHERE unread != 1');
    $stmtFeedback->execute();
    $feedbackCount = $stmtFeedback->fetchColumn();

    $stmtReport = $pdo->prepare('SELECT COUNT(*) as count FROM tbl_post_report WHERE unread != 1');
    $stmtReport->execute();
    $reportCount = $stmtReport->fetchColumn();

    $totalCount = $userCount + $feedbackCount + $reportCount;

    echo json_encode(['count' => $totalCount]);
}




if (isset($_POST['add_review'])) {
    $id = $_POST['id'];
    $user_id = $_SESSION['user'];
    $rating = $_POST['rating'];
    $hazard = $_POST['hazard'];
    $review = $_POST['review'];
    $uploadDir = '../admin/review_image/';
    $imagePaths = [];

    if (!empty($_FILES['images']['name'][0])) {
        $fileCount = count($_FILES['images']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = basename($_FILES['images']['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFilePath)) {
                $imagePaths[] = $fileName;
            } else {
                echo "Failed to upload image: " . $_FILES['images']['name'][$i];
            }
        }
    }
    $images = implode(',', $imagePaths);
    $result = addReview($pdo, $id, $user_id, $rating, $hazard, $review, $images);

    if ($result) {
        header('Location: ../user/write_review.php');
    } else {
        echo "An error occurred while adding review!";
    }
}

if (isset($_GET['get_post_comment'])) {
    $post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $user_img = $_SESSION['profile_img'];

    $userId = $_SESSION['user'];


    $reaction_statement = $pdo->prepare("SELECT 1 FROM tbl_reaction WHERE user_id = ? AND post_id = ?");
    $reaction_statement->execute([$userId, $post_id]);
    $has_reacted = $reaction_statement->fetchColumn();

    $icon_class = $has_reacted ? 'fas fa-heart' : 'far fa-heart';

    $count_statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_reaction WHERE post_id = ?");
    $count_statement->execute([$post_id]);
    $reaction_count = $count_statement->fetchColumn();

    $count_statement1 = $pdo->prepare("SELECT COUNT(*) FROM tbl_post_comment WHERE post_id = ?");
    $count_statement1->execute([$post_id]);
    $comment_count = $count_statement1->fetchColumn();




    $result = getPostComment($pdo, $post_id);
    if ($result) {


        // Display the results
        foreach ($result as $row) {

            $date = $row['date_created'];
            $datePosted = new DateTime($date);
            $now = new DateTime();

            $interval = $datePosted->diff($now);
            $timeDifference = '';
            if ($interval->y > 0) {
                $timeDifference = $interval->y . 'y';
            } elseif ($interval->m > 0) {
                $timeDifference = $interval->m . 'm';
            } elseif ($interval->days > 0) {
                $timeDifference = $interval->days . 'd';
            } elseif ($interval->h > 0) {
                $timeDifference = $interval->h . 'h';
            } elseif ($interval->i > 0) {
                $timeDifference = $interval->i . 'm';
            } else {
                $timeDifference = $interval->s . 's';
            }



            echo '
                <style>
        /* Custom slide-up animation */
        .modal.fade .modal-dialog {
            transform: translateY(100%);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }
    </style>
            
            <div class="row align-items-center">
            <!-- Back Button -->
            <div class="col-4 text-left">
                <button type="button" class="btn btn-default bg-transparent border-0"  data-dismiss="modal">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <!-- Title -->
            <div class="col-4 text-center">
                <p class="mb-0">Add Comment</p>
            </div>
            <!-- Publish Button -->
            <div class="col-4 text-right">
                   <div class="mr-auto text-right">
                            <div class="dropdown">
                                <button class="btn btn-white btn-sm border-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">.</a>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
        <hr>
        
            
                    <div style="height:650px; overflow:scroll;">
                    <div class="row">   
                        <div class="col-auto m-auto">';

            if ($row['profile_img'] != "") {
                echo '<img src="../admin/profile_image/' . $row['profile_img'] . '" style="width:50px; margin-top:-5px;" class="img-circle">';
            } else {
                echo '<img src="../dist/img/avatar2.png" style="width:30px; margin-top:-5px;" class="img-circle">';
            }
            echo ' </div>
                        
                        <div class="col" style="margin-left:-10px;">
                            <p>' . $row['name'] . '</p>
                            <h6 style="margin-top:-17px;" class="text-muted">@' . $row['username'] . ' • ' . $timeDifference . '</h6>
                        </div>
                        
                    
                    </div>  
                    
                    <div class="row">
                        <div class="col">
                            <div>
                                 <p>' . $row['post'] . '</p>

                            </div>
                        </div>
                    </div>
                    
                    
                    
                  
                    <div class="row">
                        <div class="col-auto">
                            <div class="d-inline-flex align-items-start">
                                <button class="btn btn-light bg-transparent btn-sm border-0 reactionButton" data-id="' . $post_id . '">
                                    <i class="' . $icon_class . ' text-danger" style="font-size:20px;"></i>
                                </button> 
                                <span class="badge bg-secondary position-relative" style="font-size:10px;top: -0.5em;">' . $reaction_count . '</span>
                            </div>
                        </div>
                        
                        <div class="col-auto">
                            <div class="d-inline-flex align-items-start">
                                <button class="btn btn-light bg-transparent btn-sm border-0 addComment" data-id="' . $post_id . '">
                                    <i class="far fa-comment-alt text-secondary" style="font-size:20px;"></i>
                                </button>
                                <span class="badge bg-secondary position-relative" style="font-size:10px;top: -0.5em;">' . $comment_count . '</span>
                            </div>
                        </div>
                        <div class="col mr-auto text-right">
                            <img src=""">
                        </div>
                    </div>
                    <hr>
                    <p>All comments</p>
                    
                        <div class="row mb-2">   
                        <div class="col-auto" style="margin-top:15px;">
                        <img src="../admin/profile_image/' . $user_img . '" style="width:30px;" class="img-circle">                           
                        </div>
                        
                        <div class="col">
                          <form id="commentForm" method="POST">
                            <div class="bg-light p-2" style="border-radius:5px;">
                                <h6 class="font-weight-bold">' . htmlspecialchars($_SESSION['name']) . '</h6>
                                <input type="hidden" name="id" value="' . $post_id . '">
                                <textarea id="comment" name="message" class="form-control" rows="2" placeholder="Type your comment here..." required></textarea>
                                <div class="text-right mr-auto">
                                    <button id="postButton" type="submit" name="add_comment" class="btn btn-primary btn-sm mt-1"><i class="fas fa-paper-plane"></i> Post</button>
                                </div>
                            </div>    
                        </form>
                       
                        </div>
                  </div>
                  
                  
 
 
         <script>
                 
 
$(document).ready(function() {
$("#commentForm").on("submit", function(e) {
    e.preventDefault(); // Prevent the default form submission

    var formData = $(this).serialize(); // Serialize form data

    $.ajax({
        url: "../inc/function.php?add_comment", // Updated URL to send the request to
        type: "POST", // Method of the request
        data: formData, // Form data
        dataType: "text", // Expect plain text response
        success: function(response) {
            if (response === "success") {
                console.log("Comment posted successfully!");
                $("#comment").val(""); // Clear textarea
                $("#postButton").hide(); // Hide button if desired
            } else if (response.startsWith("error:")) {
                // Log error message from response
                console.error("An error occurred:", response.substring(6)); // Remove "error:" prefix
            } else {
                console.error("Unexpected response:", response);
            }
        },
        error: function(xhr, status, error) {
            // Handle and log the error
            console.error("An error occurred:", status, error);
            console.error("Response Text:", xhr.responseText); // Log the raw response text
        }
    });
});


    // Show or hide the button based on textarea content
    $("#comment").on("input", function() {
        if ($(this).val().trim() !== "") {
            $("#postButton").show();
        } else {
            $("#postButton").hide();
        }
    });
});

    </script>
    
                    
            ';

        }
    } else {
        echo json_encode(['error' => 'No data found.']);
    }
}



if (isset($_GET['get_comment'])) {



    $query = "SELECT * FROM tbl_post_comment p LEFT JOIN tbl_user u ON u.id = p.user_id WHERE post_id = :post_id ORDER BY p.id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $date = $row['date_created'];
        $datePosted = new DateTime($date);
        $now = new DateTime();

        $interval = $datePosted->diff($now);
        $timeDifference = '';
        if ($interval->y > 0) {
            $timeDifference = $interval->y . 'y';
        } elseif ($interval->m > 0) {
            $timeDifference = $interval->m . 'm';
        } elseif ($interval->days > 0) {
            $timeDifference = $interval->days . 'd';
        } elseif ($interval->h > 0) {
            $timeDifference = $interval->h . 'h';
        } elseif ($interval->i > 0) {
            $timeDifference = $interval->i . 'm';
        } else {
            $timeDifference = $interval->s . 's';
        }


        echo '
              
                 <div class="row">   
                        <div class="col-auto" style="margin-top:13px;">
                            <i class="far fa-user-circle fa-2x m-auto"></i>
                        </div>
                        
                        <div class="col">
                        <div class="bg-light p-2" style="border-radius:5px;">
                            <h6 class="font-weight-bold">' . $row['name'] . '</h6>
                            <p style="margin-top:-5px; margin-bottom:-3px;">' . $row['message'] . '</p>
                        </div>    
                            <h6 style=" font-size:12px;" class="text-muted">' . $timeDifference . '</h6>
                        </div>
                          </div>
              ';
    }
    echo '</div>';
}

function setMessage($message, $response)
{
    $_SESSION['message'] = $message;
    $_SESSION['response'] = $response;
}

// Function to view document
function viewDocument($pdo, $userId)
{
    $stmt = $pdo->prepare("SELECT id_front, id_back FROM tbl_user WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        return [
            'frontImage' => $document['id_front'],
            'backImage' => $document['id_back']
        ];
    } else {
        return null;
    }
}
function totalDestinations($pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM tbl_location");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function totalUsers($pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(id) AS total FROM tbl_user");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}


function approveUser($pdo, $userId)
{
    $query = "UPDATE tbl_user SET status = 1 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId);
    if ($stmt->execute()) {
        setMessage('User approved successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to decline user.', 'Error');
        return false;
    }
}

function declineUser($pdo, $userId)
{
    $query = "UPDATE tbl_user SET status = 0 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId);
    if ($stmt->execute()) {
        setMessage('User declined successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to decline user.', 'Error');
        return false;
    }
}

// Function to add feedback response
function addFeedbackResponse($pdo, $feedbackId, $adminId, $responseText)
{
    $query = "INSERT INTO tbl_feedback_respond (feedback_id, user_id, message) VALUES (:feedback_id, :admin_id, :respond_text)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':feedback_id', $feedbackId);
    $stmt->bindParam(':admin_id', $adminId);
    $stmt->bindParam(':respond_text', $responseText);
    if ($stmt->execute()) {
        setMessage('Feedback response added successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to add feedback response.', 'Error');
        return false;
    }
}

// Function to get all feedback
function getFeedback($pdo)
{
    $query = "SELECT id, rate, name, location, date_created FROM tbl_feedback";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to add an announcement
function addAnnouncement($pdo, $title, $description, $image)
{
    $query = "INSERT INTO tbl_announcement (title, description, image) VALUES (:title, :description, :image)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $image);
    if ($stmt->execute()) {
        setMessage('Announcement added successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to add announcement.', 'Error');
        return false;
    }
}



function updateAnnouncement($pdo, $id, $title, $description, $image = null)
{
    $query = "UPDATE tbl_announcement SET title = :title, description = :description";
    if ($image !== null) {
        $query .= ", image = :image";
    }
    $query .= " WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    if ($image !== null) {
        $stmt->bindParam(':image', $image);
    }
    if ($stmt->execute()) {
        setMessage('Announcement updated successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to update announcement.', 'Error');
        return false;
    }
}


// Function to delete an announcement
function deleteAnnouncement($pdo, $id)
{
    $query = "DELETE FROM tbl_announcement WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        setMessage('Announcement deleted successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to delete announcement.', 'Error');
        return false;
    }
}



// Function to update account details
function updateAccount($pdo, $userId, $username, $email)
{
    $query = "UPDATE tbl_account SET username = :username, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        setMessage('Account details updated successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to update account details.', 'Error');
        return false;
    }
}

// Function to send a reset password email
function sendResetPasswordEmail($email, $userId)
{


    // Email Notification
    require_once(__DIR__ . '/../vendor/autoload.php');
    $config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
    $config->setApiKey('partner-key', 'fYZrVRIdQGSFvHxA');
    $apiInstance = new Brevo\Client\Api\TransactionalEmailsApi(
        new GuzzleHttp\Client(),
        $config
    );

    try {
        $verificationLink = 'http://localhost/tripsociety_latest/admin/reset_password.php?id=' . $userId;
        $emailSubject = 'Reset Password | Trip Society';
        $emailContent = '<html><body><p>To reset your password please click the link <a href="' . $verificationLink . '">Reset Password</a></p></body></html>';

        // Send email
        $sendSmtpEmail = new \Brevo\Client\Model\SendSmtpEmail([
            'subject' => $emailSubject,
            'sender' => ['name' => 'Trip Society', 'email' => 'tripsociety0@gmail.com'],
            'to' => [['name' => 'User', 'email' => $email]],
            'htmlContent' => $emailContent
        ]);

        $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
        if ($result) {
            setMessage('We sent a reset password link to your email address!', 'Success');
            return true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
    }

}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $userId)
{
    require_once(__DIR__ . '/../vendor/autoload.php');
    require_once(__DIR__ . '/config.php');
    // Create a new instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tripsociety0@gmail.com';
        $mail->Password = 'iclj sfzq qqtw vnqv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set email sender and recipient
        $mail->setFrom('tripsociety0@gmail.com', 'Trip Society');
        $mail->addAddress($email, 'Trip Society');

        // Generate verification URL
        $verificationUrl = 'https://tripsociety.net/admin/reset_password.php?id=' . urlencode($userId);

        // Content for HTML and Plain Text
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification';
        $mail->Body = "
            <p>Hi Admin</p>
            <p>Please verify your account by clicking the link below:</p>
            <p><a href=\"$verificationUrl\">Verify Your Account</a></p>
            <p>If you’re having trouble clicking the link, copy and paste it into your browser:</p>
            <p>$verificationUrl</p>";
        $mail->AltBody = "Hi Admin, Please verify your account by clicking the link: $verificationUrl";

        // Send the email
        $mail->send();
        echo json_encode(['response' => 'Success', 'message' => 'A verification email has been sent.']);
    } catch (Exception $e) {
        echo json_encode(['response' => 'Error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
}


// Function to reset password
function resetPassword($pdo, $userId, $newPassword)
{

    $query = "UPDATE tbl_account SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->bindParam(':password', $newPassword);
    if ($stmt->execute()) {
        $_SESSION['password'] = $newPassword;
        setMessage('Password reset successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to reset password.', 'Error');
        return false;
    }
}


function getUnreadNotifications($pdo)
{
    $query1 = "SELECT * FROM tbl_user WHERE unread = 0 AND (id_front IS NOT NULL AND id_back IS NOT NULL AND id_front != '' AND id_back != '')";
    $query2 = "SELECT *, f.id as id FROM tbl_feedback f LEFT JOIN tbl_user u ON u.id = f.user_id WHERE f.unread != 1 ";
    $query3 = "SELECT *, tpr.id as id FROM tbl_post_report tpr LEFT JOIN tbl_user u ON u.id = tpr.user_id WHERE tpr.unread != 1 ";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $users = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $notifications = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute();
    $reports = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    $results = [
        'users' => $users,
        'notifications' => $notifications,
        'reports' => $reports
    ];

    return $results;
}


function getAllNotifications($pdo)
{
    // Query to get all users with valid ID documents, ordered by date_created
    $query1 = "SELECT *, date_created FROM tbl_user WHERE id_front IS NOT NULL AND id_back IS NOT NULL AND id_front != '' AND id_back != ''";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $users = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Query to get notifications with LEFT JOIN on tbl_user
    $query2 = "
        SELECT tbl_feedback.*, tbl_user.username, tbl_user.email, tbl_user.name, tbl_feedback.date_created 
        FROM tbl_feedback 
        LEFT JOIN tbl_user ON tbl_feedback.user_id = tbl_user.id
    ";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $notifications = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // New query to fetch reports by joining tbl_user and tbl_post_report
    $query3 = "
        SELECT 
            tbl_post_report.*, 
            tbl_user.username, 
            tbl_user.email, 
            tbl_user.name, 
            tbl_post_report.date_created 
        FROM 
            tbl_post_report
        LEFT JOIN 
            tbl_user ON tbl_post_report.user_id = tbl_user.id
    ";

    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute();
    $reports = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // Combine results into a single array
    $results = [
        'users' => $users,
        'notifications' => $notifications,
        'reports' => $reports
    ];

    return $results;
}

// Function to get location count
function getLocationCount($pdo)
{
    $query = "SELECT COUNT(id) as count FROM tbl_location";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}


// Function to get location count


// Function to get user count
function getUserCount($pdo)
{
    $query = "SELECT COUNT(id) as count FROM tbl_user";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}



function addPost($pdo, $post, $location, $user_id, $images)
{
    // Set default timezone
    date_default_timezone_set('Asia/Manila');
    $currentTimestamp = date('Y-m-d H:i:s'); // Format timestamp as 'YYYY-MM-DD HH:MM:SS'

    // Prepare the post insertion query
    $query = "INSERT INTO tbl_post (user_id, post, location, image, date_created) 
              VALUES (:user_id, :post, :location, :images, :date_created)";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':post', $post);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':date_created', $currentTimestamp);

    // Process the images
    $imagePaths = [];
    $uploadDir = '../admin/post_image/';  // Directory to save uploaded images

    if (isset($images['tmp_name']) && is_array($images['tmp_name'])) {
        foreach ($images['tmp_name'] as $key => $tmpName) {
            if ($images['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($images['name'][$key]);
                $filePath = $uploadDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($tmpName, $filePath)) {
                    $imagePaths[] = $fileName;
                } else {
                    setMessage('Failed to upload image: ' . $fileName, 'Error');
                    return false;
                }
            } else {
                setMessage('Error uploading file: ' . $images['name'][$key], 'Error');
                return false;
            }
        }
    }

    $imagesString = implode(',', $imagePaths);
    $stmt->bindParam(':images', $imagesString);

    // Execute the query
    if ($stmt->execute()) {
        setMessage('Post published successfully.', 'Success');
        return true;
    } else {
        setMessage('Failed to publish post!', 'Error');
        return false;
    }
}


// Function to get user count
function getPostComment($pdo, $post_id)
{
    $query = "SELECT *, p.date_created as date_created FROM tbl_post p LEFT JOIN tbl_user u ON u.id = p.user_id WHERE p.id = $post_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function addComment($pdo, $id, $user_id, $message)
{

    try {
        $stmt = $pdo->prepare("INSERT INTO tbl_post_comment (post_id, user_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$id, $user_id, $message]);
        setMessage('Comment posted successfully.', 'Success');

        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}


function addReview($pdo, $id, $user_id, $rating, $hazard, $review, $images)
{
    try {
        $stmt = $pdo->prepare("INSERT INTO tbl_review (location_id, user_id, rating, hazard, review, images) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $user_id, $rating, $hazard, $review, $images]);
        setMessage('Review added successfully.', 'Success');
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}



?>