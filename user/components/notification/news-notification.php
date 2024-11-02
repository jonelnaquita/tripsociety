<style>
    .notification-card {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: white;
        /* Text color to contrast with the background */
        height: 200px;
        /* Adjust height as needed */
    }

    .notification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        /* Color overlay for blend */
        z-index: 1;
        /* Make sure the overlay is on top of the background */
    }

    .notification-card .card-body {
        position: relative;
        z-index: 2;
        /* Ensure content is above the overlay */
    }

    .notification-img {
        width: 150px;
        height: auto;
    }

    .card-text {
        font-size: 0.9rem;
        /* Smaller paragraph text */
    }

    .date-posted {
        font-size: 0.8rem;
        /* Smaller font for date posted */
        color: #e0e0e0;
        /* Light color for date text */
    }

    /* Background image styling */
    .notification-card {
        /* Background image */
        background-size: cover;
        background-position: center;
    }
</style>
<div class="tab-pane fade show active" id="news" role="tabpanel" aria-labelledby="news-tab">
    <!-- Cards will be injected here by the AJAX call -->
</div>

<script>
    $(document).ready(function () {
        fetchAnnouncements();

        function fetchAnnouncements() {
            $.ajax({
                url: 'api/notification/fetch-announcement.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    let announcements = '';
                    response.forEach(function (announcement) {
                        announcements += `
                            <div class="container mt-2">
                            <a href="announcement.php?id=${announcement.id}">
                                <div class="card notification-card d-flex flex-row" style="background-image: url('../admin/announcement/${announcement.image}');">
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold">${announcement.title}</h5>
                                        <p class="card-text mt-5">${announcement.description}</p>
                                        <div class="date-posted">
                                            <i class="material-icons" style="vertical-align: middle; font-size: 13px;">access_time</i>
                                            ${announcement.date_created}
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        `;
                    });
                    $('#news').html(announcements);
                },
                error: function () {
                    alert('Failed to fetch announcements');
                }
            });
        }
    });
</script>