<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Preferences</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff;
            color: #582fff;
            font-family: 'Arial', sans-serif;
        }

        .login-box {
            text-align: center;
            margin-top: 50px;
            border-radius: 15px;
            padding: 20px;
        }

        .btn-checkbox {
            width: 100%;
            border-radius: 15px;
            border: 2px solid #582fff;
            background: transparent;
            color: #582fff;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-size: 16px;
            margin: 5px 0;
        }

        .btn-checkbox.selected {
            background-color: #582fff;
            color: #fff;
            border: 2px solid #582fff;
        }

        .btn-icon {
            margin-right: 10px;
            font-size: 20px;
        }

        .btn-secondary {
            background-color: #582fff;
            color: #ffffff;
            border-radius: 20px;
            font-size: 22px;
            padding: 10px 30px;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .btn-secondary:hover {
            background-color: #4a1fff;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h1 class="font-weight-bold">What is your <br> travel preference?</h1>
        <form action="../inc/function.php" method="post">

            <div class="row m-auto" style="max-width: 600px;">
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Nature">
                        <span class="btn-icon">🌳</span> Nature
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Mountain">
                        <span class="btn-icon">⛰️</span> Mountain
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Historical">
                        <span class="btn-icon">🏛️</span> Historical
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Beach">
                        <span class="btn-icon">🏖️</span> Beach
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Church">
                        <span class="btn-icon">⛪</span> Church
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Cultural">
                        <span class="btn-icon">🎭</span> Cultural
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-checkbox" type="button" data-value="Relaxation">
                        <span class="btn-icon">💆‍♂️</span> Relaxation
                    </button>
                </div>
            </div>

            <input type="hidden" id="travelPreferences" name="travel_preferences">

            <div>
                <button type="submit" name="add_travel_preference" class="btn btn-secondary">Next</button>
            </div>
        </form>
    </div>

    <script>
        // Get all checkbox buttons
        const buttons = document.querySelectorAll('.btn-checkbox');

        // Add click event to toggle selected state
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                button.classList.toggle('selected');

                // Update hidden input with selected values
                const selectedValues = Array.from(buttons)
                    .filter(btn => btn.classList.contains('selected'))
                    .map(btn => btn.getAttribute('data-value'));

                document.getElementById('travelPreferences').value = selectedValues.join(', ');
            });
        });
    </script>
</body>

</html>