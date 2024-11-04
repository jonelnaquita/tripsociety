<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-modal-label" aria-hidden="true">
    <div class="modal-dialog" style="margin-top:170px;" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title font-weight-bold text-center" id="filter-modal-label">Filters</h5>

                <div class="row mt-4">
                    <div class="col">
                        <h6>APPLIED FILTERS</h6>
                        
                        <?php 
                        include '../inc/config.php';

                        $categories = [];
                        $id = 5;

                        // Fetch user preferences if available
                        try {
                            $stmt = $pdo->prepare('SELECT travel_preferences FROM tbl_user WHERE id = :user_id');
                            $stmt->execute(['user_id' => $id]);
                            $result = $stmt->fetchColumn();
                            
                            if ($result) {
                                $categories = explode(',', $result);
                                $categories = array_map('trim', $categories);
                            }
                        } catch (PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        }

                        // Define all preferences
                        $allPreferences = [
                            'Nature' => 'Nature',
                            'Beach' => 'Beach',
                            'Mountain' => 'Mountain',
                            'Historical' => 'Historical',
                            'Church' => 'Church',
                            'Cultural' => 'Cultural',
                            'Relaxation' => 'Relaxation',
                        ];

                        // Filter out additional preferences that are not already selected
                        $additionalPreferences = array_filter($allPreferences, function($value, $key) use ($categories) {
                            return !in_array($key, $categories);
                        }, ARRAY_FILTER_USE_BOTH);

                        // Sample cities (adjust as needed)
                        $allCities = [
                            'Agoncillo',
                            'Alitagtag',
                            'Balayan',
                            'Balete',
                            'Batangas City',
                            'Bauan',
                            'Calaca',
                            'Calatagan',
                            'Cuenca',
                            'Ibaan',
                            'Laurel',
                            'Lemery',
                            'Lian',
                            'Lipa',
                            'Lobo',
                            'Mabini',
                            'Malvar',
                            'Mataas na Kahoy',
                            'Nasugbu',
                            'Padre Garcia',
                            'Rosario',
                            'San Jose',
                            'San Juan',
                            'San Luis',
                            'San Nicolas',
                            'San Pascual',
                            'Santa Teresita',
                            'Santo Tomas',
                            'Taal',
                            'Talisay',
                            'Tanauan',
                            'Taysan',
                            'Tingloy',
                            'Tuy'
                        ];

                        ?>

                        <!-- Display selected categories and cities -->
                        <div class="selected-values mt-3">
                            <p id="selected-categories"> <!-- This will be updated dynamically --> </p>
                            <span id="selected-cities"> <!-- This will be updated dynamically --> </span>
                        </div>
                        <hr>

                        <!-- The rest of your form -->
                        <div class="radio-group mt-3">
                            <h6>CATEGORIES</h6>
                            <?php
                            // Display user-selected categories first
                            foreach ($categories as $category) {
                                echo '<label>
                                        <input type="radio" name="category" value="' . htmlspecialchars($category) . '" checked>
                                        ' . htmlspecialchars($category) . '
                                      </label><br>';
                            }
                            ?>

                            <div id="additional-preferences" style="display: none;">
                                <?php
                                // Additional preferences that were not previously displayed
                                foreach ($additionalPreferences as $label => $value) {
                                    echo '<label>
                                            <input type="radio" name="category" value="' . htmlspecialchars($value) . '">
                                            ' . htmlspecialchars($label) . '
                                          </label><br>';
                                }
                                ?>
                            </div>
                        </div>

                        <a href="#" id="show-more" onclick="togglePreferences()">Show More</a>

                        <div class="checkbox-group mt-3">
                            <h6>LOCATIONS</h6>
                            <div id="city-checkboxes">
                                <?php 
                                // Display selected cities first
                                $selectedCities = []; // This should be set dynamically if you have saved cities
                                $shownCities = array_slice($allCities, 0, 3);
                                foreach ($shownCities as $city): ?>
                                    <label>
                                        <input type="checkbox" name="location[]" value="<?php echo htmlspecialchars($city); ?>"
                                               <?php echo in_array($city, $selectedCities) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($city); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>

                            <!-- Hidden checkboxes for the rest of the cities -->
                            <div id="more-cities" style="display: none;">
                                <?php 
                                $hiddenCities = array_slice($allCities, 3);
                                foreach ($hiddenCities as $city): ?>
                                    <label>
                                        <input type="checkbox" name="location[]" value="<?php echo htmlspecialchars($city); ?>"
                                               <?php echo in_array($city, $selectedCities) ? 'checked' : ''; ?>>
                                        <?php echo htmlspecialchars($city); ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>

                            <a href="#" id="show-more1" onclick="toggleCities()">Show More</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm clear-filters" id="clear-filters">Clear Filter</button>
                <button type="button" class="btn btn-primary btn-sm" id="apply-filters" data-dismiss="modal">Apply Filters</button>
            </div>
        </div>
    </div>
</div>