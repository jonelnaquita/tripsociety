<!-- Modal HTML -->
<div class="modal fade" id="idModal" tabindex="-1" aria-labelledby="idModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="idModalLabel">User ID Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h6 class="mb-4">Front</h6>
                    <div id="frontContainer" class="image-container">
                        <img id="idFront" src="" alt="ID Front" class="img-fluid">
                        <div class="no-image" id="noFront">No ID Front Uploaded</div>
                    </div>
                    <h6 class="mt-4 mb-4">Back</h6>
                    <div id="backContainer" class="image-container">
                        <img id="idBack" src="" alt="ID Back" class="img-fluid">
                        <div class="no-image" id="noBack">No ID Back Uploaded</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .image-container {
        position: relative;
        width: 100%;
        height: 300px;
        /* Set a fixed height for consistency */
        overflow: hidden;
        border: 1px solid #dee2e6;
        /* Border for visual separation */
        border-radius: 4px;
        background-color: #f8f9fa;
        /* Light background */
        display: flex;
        justify-content: center;
        align-items: center;
        margin: auto;
    }

    .image-container img {
        max-height: 100%;
        max-width: 100%;
        display: none;
        /* Hide by default */
    }

    .no-image {
        display: none;
        /* Hide by default */
        color: #6c757d;
        /* Gray color for text */
        font-size: 1rem;
        text-align: center;
        padding: 20px;
        /* Spacing */
    }
</style>