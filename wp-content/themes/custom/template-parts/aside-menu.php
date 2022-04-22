<div class="aside-menu offcanvas offcanvas-start" id="aside-menu" tabindex="-1" aria-labelledby="offcanvas-top-label">
    <div class="offcanvas-header justify-content-end">
        <h5 id="offcanvas-top-label" class="sr-only">Navigation</h5>
        <button type="button" class="aside-menu-btn-close btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="aside-menu-container d-flex flex-column">
            <?php echo function_exists('header_nav') ? header_nav('aside-nav') : ''; ?>
        </div>
    </div>
</div>
