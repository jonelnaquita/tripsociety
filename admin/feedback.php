<?php
include '../inc/session.php';
include "includes/header.php";
include '../inc/config.php'; ?>

<body class="g-sidenav-show  bg-gray-100">
    <?php include "includes/sidebar.php"; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?php include "includes/navbar.php"; ?>
        <div class="container-fluid py-2">
            <div class="content">
                <div class="container-fluid">

                    <style>
                        .response-form {
                            display: none;
                            /* Initially hidden */
                            margin-left: 30px;
                        }

                        .response-form textarea {
                            width: 100%;
                            margin-top: 10px;
                            resize: vertical;
                        }

                        .response-form .btn {
                            margin-top: 10px;
                        }

                        .response-display {
                            margin-left: 30px;
                        }
                    </style>

                    <?php

                    if (isset($_GET['id'])) {
                        $feedbackId = $_GET['id'];
                        $update_query = "UPDATE tbl_feedback SET unread = 1 WHERE id = :id";
                        $update_stmt = $pdo->prepare($update_query);
                        $update_stmt->bindParam(':id', $feedbackId, PDO::PARAM_INT);
                        $update_stmt->execute();
                    }

                    $query = "
                SELECT tf.*, tu.name, tu.location, tf.date_created as date
                FROM tbl_feedback tf
                LEFT JOIN tbl_user tu ON tu.id = tf.user_id
            ";
                    $params = [];

                    if (isset($_GET['id'])) {
                        $feedbackId = intval($_GET['id']);
                        $query .= " WHERE tf.id = :feedback_id";
                        $params[':feedback_id'] = $feedbackId;
                    }

                    $pdo_statement = $pdo->prepare($query);
                    $pdo_statement->execute($params);
                    $result = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($result)) {
                        foreach ($result as $row) {
                            $feedback_id = $row['id'];
                            $user_id = $_SESSION['id'];

                            $response_statement = $pdo->prepare("
                        SELECT * FROM tbl_feedback_respond
                        WHERE feedback_id = :feedback_id AND user_id = :user_id
                    ");
                            $response_statement->bindParam(':feedback_id', $feedback_id, PDO::PARAM_INT);
                            $response_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                            $response_statement->execute();
                            $response = $response_statement->fetch(PDO::FETCH_ASSOC);
                            ?>


                            <div class="row">
                                <div class="col">
                                    <div class="card p-3 card-outline card-primary">
                                        <div class="col-md-12 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <img src="../dist/img/avatar5.png" style="width:50px;"
                                                                        class="img-circle" alt="">
                                                                </div>
                                                                <div class="col">
                                                                    <span
                                                                        class="font-weight-bold"><?php echo htmlspecialchars($row['name']); ?></span>
                                                                    <br>
                                                                    <span
                                                                        style="font-size:15px;"><?php echo htmlspecialchars($row['date']); ?>
                                                                    </span>
                                                                    <span style="font-size:15px;">•
                                                                        <?php echo htmlspecialchars($row['location']); ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="row ml-5 pl-1" style="margin-top:-3px;">
                                                                <div class="col">
                                                                    <?php
                                                                    $improvements = explode(',', $row['app_improvement']);
                                                                    foreach ($improvements as $improvement) {
                                                                        if (trim($improvement != 0)) {
                                                                            echo '<span class="badge bg-gradient-primary" style="font-size:12px;">' . htmlspecialchars(trim($improvement)) . '</span> ';

                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div>
                                                                <?php
                                                                $rate = $row['rate'];
                                                                for ($i = 1; $i <= 5; $i++) {
                                                                    if ($i <= $rate) {
                                                                        echo '<i class="fas fa-star text-dark"></i>';
                                                                    } else {
                                                                        echo '<i class="far fa-star text-dark"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                                <i class="fas fa-smile"></i>
                                                            </div>
                                                            <button class="btn btn-outline-dark btn-sm mt-2 respond-button"
                                                                data-feedback-id="<?php echo $feedback_id; ?>">
                                                                Respond <i class="fas fa-reply"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <p style="font-size:15px;">
                                                                <?php echo htmlspecialchars($row['feedback']); ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="response-form" id="response-form-<?php echo $feedback_id; ?>">
                                                        <form action="../inc/function.php" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $feedback_id; ?>">
                                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                                                            <div class="row response-display">
                                                                <div class="col-auto mt-2">
                                                                    <img src="../dist/img/avatar4.png" style="width:30px;"
                                                                        class="img-circle" alt="">
                                                                </div>
                                                                <div class="col">
                                                                    <span class="font-weight-bold text-muted"
                                                                        style="font-size:15px;"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                                                                    <br>
                                                                    <textarea name="message" class="form-control" rows="2"
                                                                        style="font-size:12px;"
                                                                        placeholder="Type your response here..."
                                                                        required></textarea>
                                                                    <button type="submit" name="add_respond"
                                                                        class="btn btn-primary btn-sm">Submit</button>
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm cancel-button">Cancel</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <?php if ($response): ?>
                                                        <div class="row response-display">
                                                            <div class="col-auto mt-2">
                                                                <img src="../dist/img/avatar4.png" style="width:30px;"
                                                                    class="img-circle" alt="">
                                                            </div>
                                                            <div class="col">
                                                                <span class="font-weight-bold text-muted"
                                                                    style="font-size:15px;"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                                                                <br>
                                                                <p style="font-size:15px;">
                                                                    <?php echo htmlspecialchars($response['message']); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>

                    <script>
                        document.querySelectorAll('.respond-button').forEach(button => {
                            button.addEventListener('click', function () {
                                const feedbackId = this.getAttribute('data-feedback-id');
                                document.getElementById(`response-form-${feedbackId}`).style.display = 'block';
                            });
                        });

                        document.querySelectorAll('.cancel-button').forEach(button => {
                            button.addEventListener('click', function () {
                                this.closest('.response-form').style.display = 'none';
                            });
                        });
                    </script>
                </div>
            </div>

            <?php include "includes/footer.php"; ?>
        </div>
    </main>
</body>

<script>
    window.onload = function () {
        var element = document.getElementById('accounts');
        element.classList.add('active');
    };
</script>




<script>
    var titleElement = document.getElementById("title");
    titleElement.innerHTML = "Feedbacks";

    window.onload = function () {
        var element = document.getElementById('feedbacks');
        element.classList.add('active');
    };
</script>