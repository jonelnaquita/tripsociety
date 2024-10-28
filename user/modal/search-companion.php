<style>
    .bottom-sheet2 {
        position: fixed;
        bottom: 0;
        height: 90%;
        /* Occupy most of the screen */
        width: 100%;
        /* Full width in mobile view */
        background-color: #fff;
        box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
        /* Smooth transition */
        transform: translateY(100%);
        /* Start off-screen */
        z-index: 1000;
        /* On top of other content */
        border-top-left-radius: 20px;
        /* Rounded corners */
        border-top-right-radius: 20px;
        /* Rounded corners */
    }

    .bottom-sheet2.show {
        transform: translateY(0);
        /* Bring it into view */
    }

    .bottom-sheet2.desktop {
        position: fixed;
        /* Fix position for desktop */
        top: 0;
        /* Align to the top */
        right: 0;
        /* Align to the right */
        bottom: 0;
        /* Align to the bottom to remove any space */
        width: 30%;
        /* Limit width in desktop view */
        transform: translateX(100%);
        /* Start off-screen from the right */
        z-index: 1000;
        /* Ensure it's on top of other content */
        overflow-y: auto;
        /* Enable scrolling if content overflows */
        margin-top: 5%;
    }

    .bottom-sheet2.desktop.show {
        transform: translateX(0);
        /* Bring it into view in desktop */
    }

    .bottom-sheet-content {
        padding: 20px;
        height: 100%;
        /* Ensure content fills the sheet */
        overflow-y: auto;
        /* Enable scrolling if content overflows */
    }

    .slide-icon {
        width: 40px;
        /* Width of the horizontal line */
        height: 5px;
        /* Height of the horizontal line */
        background-color: #ccc;
        /* Color of the line */
        border-radius: 5px;
        /* Rounded corners for the line */
        margin: 10px auto;
        /* Center the line */
        cursor: pointer;
        /* Change cursor to pointer */
    }

    .overlay {
        display: none;
        /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
        /* Below the bottom sheet */
    }

    .overlay.show {
        display: block;
        /* Show the overlay */
    }

    /* Search input styles */
    #searchInput {
        border-radius: 30px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    /* Custom styles for the search results */
    .search-results {
        max-height: 400px;
        /* Adjust height as needed */
        overflow-y: auto;
        /* Enable scrolling */
        margin-top: 15px;
    }

    /* Friend item styles */
    .friend-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: white;
        transition: box-shadow 0.3s ease;
        border: 1px solid #ddd;
    }

    .friend-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .friend-item img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .friend-info {
        display: flex;
        flex-direction: column;
    }

    .friend-info strong {
        font-size: 18px;
        color: #333;
        margin-bottom: 2px;
    }

    .friend-info small {
        color: #666;
        font-size: 14px;
    }

    .search-results .p-2 {
        color: #888;
        font-size: 14px;
        text-align: center;
    }

    /* Responsive adjustments */
    @media (min-width: 769px) {
        .bottom-sheet {
            display: none;
            /* Hide mobile bottom sheet */
        }

        .bottom-sheet.desktop {
            display: block;
            /* Show desktop bottom sheet */
        }
    }
</style>


<!-- Overlay -->
<div id="overlay" class="overlay" onclick="closeBottomSheet()"></div>

<!-- Search Bottom Sheet (Mobile) -->
<div id="searchBottomSheet" class="bottom-sheet2">
    <div class="slide-icon" onclick="closeBottomSheet()"></div> <!-- Horizontal line -->
    <div class="bottom-sheet-content">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for friends..."
            onkeyup="filterFriends('searchInput', 'searchResults')">
        <div class="search-results mt-2" id="searchResults"></div>
    </div>
</div>

<!-- Search Bottom Sheet (Desktop) -->
<div id="searchBottomSheetDesktop" class="bottom-sheet2 desktop">
    <div class="slide-icon" onclick="closeBottomSheet()"></div> <!-- Horizontal line -->
    <div class="bottom-sheet-content">
        <input type="text" id="searchInputDesktop" class="form-control" placeholder="Search for friends..."
            onkeyup="filterFriends('searchInputDesktop', 'searchResultsDesktop')">
        <div class="search-results mt-2" id="searchResultsDesktop"></div>
    </div>
</div>


<script>
    function openBottomSheet() {
        const isDesktop = window.innerWidth >= 769;
        const bottomSheet = isDesktop ? document.getElementById('searchBottomSheetDesktop') : document.getElementById('searchBottomSheet');
        const overlay = document.getElementById('overlay');

        bottomSheet.classList.add('show'); // Show the bottom sheet
        overlay.classList.add('show'); // Show the overlay
    }

    function closeBottomSheet() {
        const bottomSheetDesktop = document.getElementById('searchBottomSheetDesktop');
        const bottomSheetMobile = document.getElementById('searchBottomSheet');
        const overlay = document.getElementById('overlay');

        if (bottomSheetDesktop.classList.contains('show')) {
            bottomSheetDesktop.classList.remove('show'); // Hide desktop bottom sheet
        } else {
            bottomSheetMobile.classList.remove('show'); // Hide mobile bottom sheet
        }
        overlay.classList.remove('show'); // Hide overlay
    }

    // Function to filter friends
    function filterFriends(inputId, resultsId) {
        const input = document.getElementById(inputId).value.toLowerCase();
        const resultsDiv = document.getElementById(resultsId);

        if (input.length === 0) {
            resultsDiv.innerHTML = ""; // Clear previous results if input is empty
            return;
        }

        // Send an AJAX request to the server
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "api/search-companion/fetch-companion.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const users = JSON.parse(xhr.responseText);
                resultsDiv.innerHTML = ""; // Clear previous results

                if (users.length > 0) {
                    users.forEach(user => {
                        const friendItem = document.createElement("div");
                        friendItem.classList.add("friend-item");

                        // Check if the user is verified and add the icon if status is 1
                        const verifiedIcon = user.status == 1 ?
                            `<i class="fas fa-check-circle" style="color: #582fff; margin-left: 3px;" title="Verified"></i>` :
                            '';

                        friendItem.innerHTML = `
                            <a href="profile.php?id=${user.id}" style="text-decoration: none; color: inherit; display: flex; align-items: center; width: 100%;">
                                <img src="../admin/profile_image/${user.profile_img}" alt="${user.name}">
                                <div class="friend-info">
                                    <strong>${user.name} ${verifiedIcon}</strong>
                                    <small>@${user.username}</small>
                                </div>
                            </a>
                        `;
                        resultsDiv.appendChild(friendItem);
                    });
                } else {
                    resultsDiv.innerHTML = "<div class='p-2'>No friends found.</div>";
                }
            }
        };
        xhr.send("searchTerm=" + input);
    }

    // Event listeners for search inputs
    document.getElementById("searchInput").addEventListener("keyup", function () {
        filterFriends("searchInput", "searchResults");
    });

    document.getElementById("searchInputDesktop").addEventListener("keyup", function () {
        filterFriends("searchInputDesktop", "searchResultsDesktop");
    });
</script>