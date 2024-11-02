<?php
header('Content-Type: application/json');
include '../../../inc/config.php'; // Your database connection

try {
    // Fetch top 5 improvements with count of respondents
    $stmt = $pdo->prepare("
        SELECT 
            improvement,
            COUNT(*) AS respondent_count
        FROM (
            SELECT 
                TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(app_improvement, ',', n.n), ',', -1)) AS improvement
            FROM 
                tbl_feedback
            JOIN 
                (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
                UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10) n
            ON CHAR_LENGTH(app_improvement) - CHAR_LENGTH(REPLACE(app_improvement, ',', '')) >= n.n - 1
        ) AS improvements
        GROUP BY improvement
        ORDER BY respondent_count DESC
        LIMIT 5
    ");
    $stmt->execute();

    $improvements = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Store both the improvement text and the respondent count
        $improvements[] = [
            'improvement' => $row['improvement'],
            'respondent_count' => $row['respondent_count']
        ];
    }

    // Calculate the average rating
    $stmt = $pdo->prepare("SELECT AVG(rate) AS average_rating FROM tbl_feedback");
    $stmt->execute();
    $avgRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $averageRating = round($avgRow['average_rating'], 1); // Rounded to 1 decimal

    // Return the data as JSON
    echo json_encode([
        'status' => 'success',
        'data' => [
            'improvements' => $improvements,
            'averageRating' => $averageRating
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>