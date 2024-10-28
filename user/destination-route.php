<?php
include 'header.php';
?>

<style>
    .bottom-sheet {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        opacity: 0;
        pointer-events: none;
        /* Ensure map is clickable */
        align-items: center;
        flex-direction: column;
        justify-content: flex-end;
        transition: 0.1s linear;
        z-index: 1;
        /* Ensure it is behind the map when minimized */
    }

    .bottom-sheet.show {
        opacity: 1;
        pointer-events: auto;
        /* Enable interaction when visible */
    }

    .bottom-sheet .content {
        width: 100%;
        position: relative;
        background: #fff;
        max-height: 100vh;
        height: 15vh;
        /* Minimized height */
        max-width: 1150px;
        padding: 25px 30px;
        transform: translateY(100%);
        border-radius: 12px 12px 0 0;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);
        transition: 0.3s ease;
    }

    .bottom-sheet.show .content {
        transform: translateY(0%);
        /* Show full height when open */
    }

    /* Ensure the map is clickable */
    #map {
        pointer-events: auto;
    }

    .bottom-sheet .sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 50;
        /* Ensure it's clickable */
        width: 100%;
        height: 100%;
        opacity: 0.4;
    }


    .bottom-sheet.dragging .content {
        transition: none;
    }

    .bottom-sheet.fullscreen .content {
        border-radius: 0;
        overflow-y: hidden;
    }

    .bottom-sheet .header {
        display: flex;
        justify-content: center;
    }

    .header .drag-icon {
        cursor: grab;
        user-select: none;
        padding: 15px;
        margin-top: -15px;
    }

    .header .drag-icon span {
        height: 4px;
        width: 40px;
        display: block;
        background: #C7D0E1;
        border-radius: 50px;
    }

    .bottom-sheet .body {
        height: 100%;
        overflow-y: auto;
        padding: 15px 0 40px;
        scrollbar-width: none;
    }

    .bottom-sheet .body::-webkit-scrollbar {
        width: 0;
    }

    .bottom-sheet .body h2 {
        font-size: 1.8rem;
    }

    .bottom-sheet .body p {
        margin-top: 20px;
        font-size: 1.05rem;
    }

    .timeline-container {
        max-height: 300px;
        overflow-y: hidden;
        position: relative;
        padding-right: 15px;
    }

    .timeline-container:hover {
        overflow-y: auto;
    }



    .timeline::-webkit-scrollbar {
        width: 0px;
        /* Removes the scrollbar */
    }
</style>

<div class="bottom-sheet">
    <div class="sheet-overlay"></div>
    <div class="content">
        <div class="header">
            <div class="drag-icon"><span></span></div>
        </div>
        <div class="body">
            <h5>Location Name</h5>
            <div class="row">
                <div class="col">
                    <h6 class="font-weight-bold">
                        <i class="fas fa-star text-warning"></i>
                        <span class="text-muted ml-2">10 Reviews</span>
                    </h6>
                    <h6 class="text-muted">Mountain</h6>
                    <button class="btn btn-secondary btn-sm" style="border-radius:25px;">
                        <i class="fas fa-directions pr-1"></i> Direction
                    </button>
                    <button class="btn btn-outline-secondary btn-sm ml-1" style="border-radius:25px;">
                        <i class="fas fa-location-arrow pr-1"></i> Start
                    </button>
                    <button class="btn btn-outline-secondary btn-sm ml-1" style="border-radius:25px;">
                        <i class="fas fa-external-link-alt pr-1"></i> Share
                    </button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <h5 class="font-weight-bold">How to get there?</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let touchStartY = 0, touchEndY = 0;
        const bottomSheet = document.querySelector(".bottom-sheet");
        const sheetOverlay = bottomSheet.querySelector(".sheet-overlay");
        const sheetContent = bottomSheet.querySelector(".content");
        const dragIcon = bottomSheet.querySelector(".drag-icon");

        let isDragging = false, startY, startHeight;

        const showBottomSheet = () => {
            bottomSheet.classList.add("show");
            document.body.style.overflowY = "hidden";
            updateSheetHeight(50); // Start by showing 50% of the bottom sheet height
        };

        const hideBottomSheet = () => {
            bottomSheet.classList.remove("show");
            document.body.style.overflowY = "auto";
            updateSheetHeight(15); // Reset height to default when hidden
        };

        const updateSheetHeight = (height) => {
            sheetContent.style.height = `${height}vh`;
            bottomSheet.classList.toggle("fullscreen", height === 100);
        };

        const dragStart = (e) => {
            isDragging = true;
            startY = e.pageY || e.touches[0].pageY;
            startHeight = parseInt(sheetContent.style.height, 10);
            bottomSheet.classList.add("dragging");
        };

        const dragging = (e) => {
            if (!isDragging) return;
            const delta = startY - (e.pageY || e.touches[0].pageY);
            const newHeight = startHeight + (delta / window.innerHeight) * 100;
            updateSheetHeight(Math.min(100, Math.max(15, newHeight)));
        };

        const dragStop = () => {
            isDragging = false;
            bottomSheet.classList.remove("dragging");
            const sheetHeight = parseInt(sheetContent.style.height, 10);
            if (sheetHeight < 25) hideBottomSheet();
        };

        dragIcon.addEventListener("mousedown", dragStart);
        document.addEventListener("mousemove", dragging);
        document.addEventListener("mouseup", dragStop);
        dragIcon.addEventListener("touchstart", dragStart);
        document.addEventListener("touchmove", dragging);
        document.addEventListener("touchend", dragStop);

        sheetOverlay.addEventListener("click", hideBottomSheet);

        // Detect swipe-up gesture to show the bottom sheet
        document.addEventListener("touchstart", (e) => {
            touchStartY = e.changedTouches[0].screenY;
        });

        document.addEventListener("touchend", (e) => {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipeGesture();
        });

        const handleSwipeGesture = () => {
            const swipeDistance = touchStartY - touchEndY;
            const swipeThreshold = 50; // Minimum swipe distance to trigger action

            if (swipeDistance > swipeThreshold) {
                // Swipe-up detected
                showBottomSheet();
            }
        };
    });

</script>