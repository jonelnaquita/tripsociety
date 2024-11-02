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

                    <div class="mt-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3">
                                <h6>Recommended areas to fix in order to improve the app (Based on user feedback)</h6>
                            </div>
                            <div class="col-md-6 text-center mb-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="box p-2 border rounded bg-gradient bg-primary text-white">
                                            <div class="number fs-5 font-weight-bold">1</div>
                                            <div class="text small">User Interface</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="box p-2 border rounded bg-gradient bg-info text-white">
                                            <div class="number fs-5 font-weight-bold">2</div>
                                            <div class="text small">Performance</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="box p-2 border rounded bg-gradient bg-warning text-dark">
                                            <div class="number fs-5 font-weight-bold">3</div>
                                            <div class="text small">Accessibility</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="box p-2 border rounded bg-gradient bg-danger text-white">
                                            <div class="number fs-5 font-weight-bold">4</div>
                                            <div class="text small">Features</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="box p-2 border rounded bg-gradient bg-success text-white">
                                            <div class="number fs-5 font-weight-bold">5</div>
                                            <div class="text small">User Support</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-end mb-3">
                                <h6>Average Rating: <strong id="average-rating">4.5</strong></h6>
                            </div>
                        </div>
                    </div>

                    <style>
                        .box {
                            height: 100px;
                            /* Set a fixed height */
                        }

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
                SELECT tf.*, tu.name, tu.location, tf.date_created AS date, tfr.message
                FROM tbl_feedback tf
                LEFT JOIN tbl_user tu ON tu.id = tf.user_id
                LEFT JOIN tbl_feedback_respond tfr ON tfr.feedback_id = tf.id
                WHERE tfr.message IS NULL;
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

                                                                    <?php
                                                                    $improvements = explode(',', $row['app_improvement']);
                                                                    foreach ($improvements as $improvement) {
                                                                        if (trim($improvement != 0)) {
                                                                            echo '<br><span class="badge bg-gradient-primary" style="font-size:10px;">' . htmlspecialchars(trim($improvement)) . '</span> ';

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

<script>
    $(document).ready(function () {
        $.ajax({
            url: 'api/feedback/fetch-app-improvement.php', // Path to your PHP file
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Update the UI with the fetched data
                    const improvements = response.data.improvements;
                    const averageRating = response.data.averageRating;

                    improvements.forEach((improvement, index) => {
                        // Update the number with the respondent count
                        $('.box').eq(index).find('.number').text(improvement.respondent_count);
                        // Update the text with the improvement description
                        $('.box').eq(index).find('.text').text(improvement.improvement);
                    });

                    // Display the average rating
                    $('#average-rating').text(averageRating);
                } else {
                    console.error('Error fetching data:', response.message);
                }
            },
            error: function (err) {
                console.error('AJAX error:', err);
            }
        });
    });
</script>