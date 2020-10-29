<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Fikri Ahmad</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" href="<?= base_url('/'); ?>">Home</a>
                <a class="nav-link" href="<?= base_url('/pages/about'); ?>">About</a>
                <a class="nav-link" href="<?= base_url('/pages/contact'); ?>">Contact</a>
                <a class="nav-link" href="<?= base_url('/comics'); ?>">Comics</a>
                <a class="nav-link" href="<?= base_url('/orang'); ?>">Orang</a>
            </div>
            <?php if (logged_in()) : ?>
                <a class="nav-item nav-link" href="/logout">Logout</a>
            <?php else : ?>
                <a class="nav-item nav-link" href="/login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>