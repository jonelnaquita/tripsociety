<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Hazard Levels</h5>
    </div>
</div>
<h6 class="">
    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $location_id = $_GET['id'];

        // Query to calculate the average hazard level
        $stmt = $pdo->prepare("
            SELECT AVG(hazard_rating) AS avg_rating
            FROM tbl_review
            WHERE location_id = :location_id
        ");
        $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['avg_rating'] !== null) {
            $avg_rating = $result['avg_rating'];

            // Determine the hazard level based on the average rating
            $hazard_text = '';
            if ($avg_rating >= 0.0 && $avg_rating <= 0.49) {
                $hazard_text = "No Hazard: This destination has been rated as completely safe by fellow visitors. Users report that pathways are well-maintained, weather conditions are stable, safety measures are sufficient, sanitation is excellent, and crowd control is effectively managed.";
            } elseif ($avg_rating >= 0.5 && $avg_rating <= 1.49) {
                $hazard_text = "Very Low Hazard: Fellow travelers have noted minimal risks at this destination. Pathways, weather, safety measures, health conditions, and security are mostly reliable, with rare or negligible issues. Basic awareness is advised.";
            } elseif ($avg_rating >= 1.5 && $avg_rating <= 2.49) {
                $hazard_text = "Low Hazard: Based on user reviews, some minor risks exist. These might include slightly challenging pathways, occasional weather changes, limited safety equipment, or mild sanitation concerns. Visitors should take standard safety precautions.";
            } elseif ($avg_rating >= 2.5 && $avg_rating <= 3.49) {
                $hazard_text = "Moderate Hazard: Users have observed moderate risks that could affect your visit, such as partially unsafe pathways, inconsistent weather, limited safety measures, or potential health and sanitation issues. Caution and preparation are recommended for a safer experience.";
            } elseif ($avg_rating >= 3.5 && $avg_rating <= 4.49) {
                $hazard_text = "High Hazard: Significant risks have been reported by travelers. Issues may include hazardous pathways, unpredictable weather, insufficient safety measures, noticeable health concerns, or inadequate security and crowd control. Proceed with extreme caution and stay informed.";
            } elseif ($avg_rating >= 4.5 && $avg_rating <= 5.0) {
                $hazard_text = "Extreme Hazard: Visitors strongly warn against traveling to this destination. Severe risks include unsafe pathways, extreme weather, a lack of safety measures, serious health hazards, or a breakdown in security. Avoid visiting until conditions improve, as per fellow users' reviews.";
            } else {
                $hazard_text = "Unknown: Insufficient data is available to determine the hazard level for this location.";
            }

            echo '<p>' . htmlspecialchars($hazard_text) . '</p>';
        } else {
            echo '<p>No reviews found for this location.</p>';
        }
    } else {
        echo '<p>Invalid ID parameter.</p>';
    }
    ?>
</h6>