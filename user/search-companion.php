<?php
include 'header.php';
?>

<style>
    /* Custom styles for the search results */
    .search-results {
        max-height: 500px;
        overflow-y: auto;
        margin-top: 15px;
    }

    .friend-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: white;
        transition: box-shadow 0.3s ease;
        border: 1px solid #ddd;
        /* Subtle border for separation */
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

    #searchInput {
        border-radius: 30px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    #searchInput:focus {
        outline: none;
        border-color: #3f51b5;
        box-shadow: 0 2px 8px rgba(63, 81, 181, 0.2);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .friend-item {
            flex-direction: row;
        }

        .friend-info strong {
            font-size: 16px;
        }

        .friend-info small {
            font-size: 13px;
        }
    }
</style>

<div class="content-wrapper">
    <div class="container mt-4">
        <div id="searchField">
            <div class="list-group">
                <div class="list-body">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for friends..."
                        onkeyup="filterFriends()">
                    <div class="search-results mt-2" id="searchResults"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterFriends() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const resultsDiv = document.getElementById("searchResults");

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
    </script>

</div>

<?php
include 'footer.php';
?>